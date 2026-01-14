# AutoSched : Automated Timetable Scheduler

AutoSched is a web-based automated timetable generator built to simplify and automate the scheduling process for colleges and universities. It ensures conflict-free timetable generation for multiple semesters, manages faculty leaves, assigns substitute teachers , and provides personalized dashboards for students, faculty, HOD, and administrators.

---

## 📌 Features

- ✅ Automated generation of conflict-free timetables.
- 🧑‍🏫 Faculty leave application and automatic substitution allocation.
- 👨‍🎓 Student, faculty, HOD, and admin dashboards with role-specific access.
- 🧠 OR-Tools based backend optimization using Python.
- 🔔 Real-time notifications on the respective dahboards for substitutions and leave approvals.
- 📋 Admin configuration of period durations, number of lectures per subject, and working days.

---

## 🖥️ Technologies Used

| Layer      | Technologies                                   |
|------------|------------------------------------------------|
| Frontend   | HTML, CSS, JavaScript                          |
| Backend    | PHP, Python (OR-Tools, JSON)                   |
| Database   | MySQL                                          |
| Server     | Apache (via XAMPP)                             |
| Libraries  | `ortools`, `json`, `random`                    |

---

## 🗃️ Database Tables

| Table Name               | Purpose                                                       |
|--------------------------|---------------------------------------------------------------|
| `faculty`                | Stores faculty IDs and names   
| `admin`                  | Stores the admin login credentials                             
| `students`               | Student login  and metadata                                   |
| `subjects`               | List of subjects with types                                   |
| `faculty_subjects`       | Stores the faculty and subject mapping                        |
| `faculty_allotment`      | Allotment of faculty to sections and subjects                 |
| `general_settings`       | Number of periods, break times, period duration, working days |
| `final_timetable`        | Final generated timetable for each section                    |
| `faculty_leaves`         | Leave records submitted by faculty                            |
| `substitution_assignments` | Substitution mapping on leave approval                      |
| `notifications`          | Student/faculty notifications                                 |
| `admin_notifications`          | admin notifications                                     |
| `semester` | Semester mappings |
| `semester_subject` | Semester and subject mappings |
| `subject_settings` | Subject settings which stores subject type and weekly lecture count |

---

## 🔧 Setup Instructions

### 1. Clone Repository

```bash
git clone https://github.com/your-username/AutoSched-Timetable-Generator.git
cd AutoSched-Timetable-Generator
---
2. Python Setup

pip install ortools

---
3. MySQL Setup (via XAMPP)
Launch XAMPP → Start Apache and MySQL

Go to localhost/phpmyadmin

Create a new database: autosched

Import autosched.sql file into the database

---
4. Update db.php Configuration

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "autosched";
$conn = new mysqli($host, $user, $pass, $dbname);

---

5. Start the Application
Visit http://localhost/AutoSched-Timetable-Generator/frontend/home.html in your browser.

## 🔧 Troubleshooting

- **MySQL Import Fails**: Ensure XAMPP MySQL is running. Check for existing database name conflicts.
- **Python Errors**: Verify `ortools` is installed (`pip list`). Use Python 3.7+.
- **Page Not Loading**: Confirm Apache is started in XAMPP. Check browser console for errors.
- **Timetable Generation Fails**: Ensure all settings are configured in admin dashboard. Check backend logs for OR-Tools errors.

🧠 Timetable Generation Logic
Implemented using Python and Google OR-Tools (cp_model)

Input format: JSON from admin settings and subject allocation

Handles constraints:

No faculty or section overlap

Subject-specific lectures per week

Max 2 lectures/day per subject

Lab blocks (3 periods in a row)

Faculty preferences (blackout days, etc.)

Output saved to generated_timetable_all.json and inserted into DB

🔄 Substitution Logic (Leave Handling)

Faculty applies for leave (multiple dates allowed)

HOD views pending leave and approves/rejects

Upon approval:

All scheduled periods for the leave faculty are checked

Substitution is assigned to another faculty with free slot (priority to faculty handling same semester/section)

Re-substitution handled if substitute is also unavailable

Notifications generated for original faculty, substitute, and students

🔔 Notification System

Recipient	Trigger	Example Message
Faculty (On Leave)	Leave approval	"✅ Your leave for Digital Logic Design on 2025-05-21 has been approved."
Substitute Faculty	Substitution assigned	"📌 You are assigned to substitute Java Programming on 2025-05-21 (Period 3)."
Students	Teacher absent + substitute available	"ℹ️ Your teacher is on leave. [Substitute] will handle your class for [Subject]."


👨‍💻 Developer
Developed by Gundaboina Sai Shashank Yadav as part of a pre-final-year academic project.



🙌 Acknowledgements
- Google OR-Tools
- XAMPP
- phpMyAdmin

---


