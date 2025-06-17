src/
├── layouts/
│   ├── AppLayout.vue (Main layout with navbar)
│   ├── AuthLayout.vue (Login/Register layout)
│   └── DashboardLayout.vue (Dashboard specific layout)
├── components/
│   ├── Navigation/
│   │   ├── Navbar.vue
│   │   ├── Sidebar.vue
│   │   └── UserMenu.vue
│   ├── Auth/
│   │   ├── Login.vue
│   │   ├── Register.vue
│   │   └── Profile.vue
│   ├── Dashboard/
│   │   ├── PatientDashboard.vue
│   │   ├── DoctorDashboard.vue
│   │   ├── AdminDashboard.vue
│   │   └── DashboardCards.vue
│   ├── Appointments/
│   │   ├── BookAppointment.vue (Multi-step booking)
│   │   ├── AppointmentList.vue
│   │   ├── AppointmentCard.vue
│   │   ├── AppointmentDetails.vue
│   │   └── AppointmentCalendar.vue
│   ├── Patients/
│   │   ├── PatientList.vue
│   │   ├── PatientProfile.vue
│   │   └── PatientSearch.vue
│   ├── Doctors/
│   │   ├── DoctorList.vue
│   │   ├── DoctorProfile.vue
│   │   ├── DoctorSchedule.vue
│   │   └── ScheduleManager.vue
│   ├── Management/
│   │   ├── DepartmentManager.vue
│   │   ├── ServiceManager.vue
│   │   └── UserManager.vue
│   ├── Common/
│   │   ├── Modal.vue
│   │   ├── DataTable.vue
│   │   ├── DatePicker.vue
│   │   ├── TimePicker.vue
│   │   ├── Loading.vue
│   │   ├── SearchBar.vue
│   │   └── Pagination.vue
│   └── Forms/
│       ├── AppointmentForm.vue
│       ├── PatientForm.vue
│       ├── DoctorForm.vue
│       └── ServiceForm.vue
├── views/
│   ├── Home.vue
│   ├── Login.vue
│   ├── Dashboard.vue
│   ├── Appointments/
│   │   ├── Index.vue
│   │   ├── Create.vue
│   │   └── Show.vue
│   ├── Patients/
│   │   ├── Index.vue
│   │   ├── Create.vue
│   │   └── Show.vue
│   └── Doctors/
│       ├── Index.vue
│       ├── Create.vue
│       └── Show.vue
├── router/
│   └── index.js (Role-based routing)
└── store/
    ├── auth.js
    ├── appointments.js
    ├── patients.js
    └── doctors.js