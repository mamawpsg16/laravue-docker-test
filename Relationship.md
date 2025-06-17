Database Relationship Summary:

User → DoctorProfile (1:1)
DoctorProfile → Departments (M:M through pivot)
DoctorProfile → Schedules (1:M)
DoctorProfile → Leaves (1:M)
DoctorProfile → Appointments (1:M)
Department → Services (1:M)
Service → Appointments (1:M)
Appointment → History (1:M)
User → Notifications (1:M)