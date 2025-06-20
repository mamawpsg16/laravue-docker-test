<?php

namespace App\Providers;

use App\Models\User;
use App\Services\Logger;
use App\Services\Firewall;
use App\Reports\SpeedReport;
use App\Services\FileLogger;
use App\Services\Transistor;
use App\Reports\MemoryReport;
use App\Contracts\UserContext;
use App\Filters\TooLongFilter;
use App\Services\ExportService;
use App\Services\PodcastParser;
use App\Services\ReportService;
use App\Filters\ProfanityFilter;
use App\Services\DatabaseLogger;
use App\Services\RequestTracker;
use App\Services\RandomGenerator;
use App\Contracts\FilterInterface;
use App\Contracts\LoggerInterface;
use App\Contracts\ParserInterface;
use App\Contracts\ReportInterface;
use App\Services\ReportAggregator;
use App\Services\HardCodedUserContext;
use App\Services\StripePaymentGateway;
use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGatewayInterface;
use App\Http\Controllers\BindingPrimitiveController;
use App\Services\ResolvingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //BIND INTERFACE TO CONCRETE CLASS
        $this->app->bind(PaymentGatewayInterface::class, StripePaymentGateway::class);

        //“When someone asks for ParserInterface, give them an instance of PodcastParser.”
        //Use this when your class depends on an interface, like:
            // class Transistor {
            //     public function __construct(ParserInterface $parser) { ... }
            // }
        
        $this->app->bind(ParserInterface::class, PodcastParser::class);

        $this->app->bind(Transistor::class, function ($app) {
            return new Transistor($app->make(PodcastParser::class));
        });

        // Bind LoggerInterface → FileLogger
        // Tells Laravel how to create a LoggerInterface
        $this->app->bind(LoggerInterface::class, FileLogger::class);

        // Full control binding for ReportService
        $this->app->bind(ReportService::class, function ($app) {
            // Tells Laravel to actually build and return an instance
            $logger = $app->make(LoggerInterface::class); // manual injection 
            $reportType = config('app.report_type', 'daily'); // read from config

            return new ReportService($logger, $reportType); // custom logic
        });

        $this->app->singleton(RandomGenerator::class, function () {
            return new RandomGenerator();
        });

        $this->app->scoped(RequestTracker::class, function () {
            return new RequestTracker();
        });

        $user = new User([
            'name' => 'Kevin',
            'email' => 'kevin@example.com',
        ]);

        $this->app->instance(User::class, $user);

        $currentUser = new User([
        'name' => 'TestUser',
            'email' => 'test@example.com',
        ]);

        $this->app->instance(UserContext::class, new HardCodedUserContext($user));

        $this->app->when(ReportService::class)
              ->needs(LoggerInterface::class)
              ->give(FileLogger::class);

        $this->app->when(ExportService::class)
                ->needs(LoggerInterface::class)
                ->give(DatabaseLogger::class);

        $this->app->when(BindingPrimitiveController::class)
          ->needs('$variable')
          ->give('Static Value');

        // Tag implementations
        // Groups related services	$this->app->tag([ClassA::class, ClassB::class], 'tag-name')
        $this->app->tag([MemoryReport::class, SpeedReport::class], 'reports');
        
        // Configure contextual binding
        $this->app->when(ReportAggregator::class)
            ->needs(ReportInterface::class)
            ->giveTagged('reports'); //Injects tagged services

        $this->app->tag([ProfanityFilter::class, TooLongFilter::class], 'filters');

        $this->app->when(Firewall::class)
            ->needs(FilterInterface::class)
            ->giveTagged('filters');

        $this->app->extend(Logger::class, function (Logger $logger, $app) {
            // Extend the existing Logger binding by decorating it with additional behavior

            return new class($logger) extends Logger {
                // Hold the original Logger instance
                protected $logger;

                // Constructor receives the original Logger instance and stores it
                public function __construct($logger) {
                    $this->logger = $logger;
                }

                // Override the log method to add extra functionality
                public function log(string $message) {
                    // First, call the original Logger's log method to preserve existing behavior
                    $this->logger->log($message);

                    // Then, add new behavior: append the log message to a custom log file
                    file_put_contents(
                        storage_path('logs/custom.log'), // Path to the custom log file
                        $message . PHP_EOL,               // Message with a newline
                        FILE_APPEND                      // Append to the file instead of overwriting
                    );
                }
            };
        });

        $this->app->when(ResolvingService::class)
        ->needs('$var')
        ->give('Hello World');

        $this->app->resolving(FileLogger::class, function (FileLogger $log, $app) {
            // Modify or configure the ReportGenerator instance after it is resolved
            $log->log('Resolved instance of ' . get_class($log));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
