import json
from ortools.sat.python import cp_model

# Load JSON input
with open("timetable_input.json", "r") as f:
    data = json.load(f)

general = data["general_settings"]
subjects = data["subjects"]
faculty_allotments = data["faculty_allotments"]

days = general["working_days"]
periods_per_day = general["periods_per_day"]
period_duration = general["period_duration"]
lab_duration = general["lab_duration"]
break_after_period = general["break_after_period"]

lab_periods = lab_duration // period_duration  # Number of consecutive periods needed for lab

sections = sorted(set(f["section"] for f in faculty_allotments))
slots = [(d, p) for d in days for p in range(1, periods_per_day + 1)]

model = cp_model.CpModel()
timetable = {}

# Create variables
for section in sections:
    timetable[section] = {}
    for subj in subjects:
        for fac in faculty_allotments:
            if fac["subject_id"] == subj["subject_id"] and fac["section"] == section:
                var_list = []
                for d in days:
                    for p in range(1, periods_per_day + 1):
                        var = model.NewBoolVar(f"{section}_{subj['subject_name']}_{d}_{p}")
                        var_list.append((d, p, var))
                timetable[section][(subj["subject_id"], fac["faculty_id"])] = (subj, fac, var_list)

# Diagnostic: Check total lectures required vs available slots per section
for section in sections:
    total_lectures = 0
    for (subject_id, faculty_id), (subj, fac, var_list) in timetable[section].items():
        total_lectures += subj["lectures_per_week"]
    total_slots = len(days) * periods_per_day
    print(f"Section {section}: Total lectures required = {total_lectures}, Total available slots = {total_slots}")

# Constraint 1: Assign exact number of lectures per subject per section
for section, subject_assignments in timetable.items():
    for (subject_id, faculty_id), (subj, fac, var_list) in subject_assignments.items():
        lecture_count = subj["lectures_per_week"]
        vars_only = [var for _, _, var in var_list]
        model.Add(sum(vars_only) == lecture_count)

# Constraint 2: Max 2 periods per subject per day
# Uncomment to enable
for section, subject_assignments in timetable.items():
     for (subject_id, faculty_id), (subj, fac, var_list) in subject_assignments.items():
         for d in days:
             daily_vars = [var for (day, p, var) in var_list if day == d]
             model.Add(sum(daily_vars) <= 2)

# Constraint 3: Only one subject per section per slot (day, period)
# Uncomment to enable
for section in sections:
     for d in days:
         for p in range(1, periods_per_day + 1):
             vars_in_slot = []
             for (subject_id, faculty_id), (subj, fac, var_list) in timetable[section].items():
                 for (day, period, var) in var_list:
                     if day == d and period == p:
                         vars_in_slot.append(var)
             model.Add(sum(vars_in_slot) <= 1)

# Constraint 4: Faculty cannot teach multiple sections at the same time
# Uncomment to enable
for d in days:
     for p in range(1, periods_per_day + 1):
         for faculty_id in set(fac["faculty_id"] for fac in faculty_allotments):
             vars_for_faculty = []
             for section in sections:
                 for (subject_id, fac_id), (subj, fac, var_list) in timetable[section].items():
                     if fac_id == faculty_id:
                         for (day, period, var) in var_list:
                             if day == d and period == p:
                                 vars_for_faculty.append(var)
             model.Add(sum(vars_for_faculty) <= 1)

# Constraint 5: Lab constraints (flexible placement of one lab block per subject per section)
# for section, subject_assignments in timetable.items():
#     for (subject_id, faculty_id), (subj, fac, var_list) in subject_assignments.items():
#         if subj["subject_type"].lower() == "lab":
#             lab_block_options = []

#             for d in days:
#                 for start_p in range(1, periods_per_day - lab_periods + 2):
#                     block = [(day, period, var) for (day, period, var) in var_list if day == d and start_p <= period < start_p + lab_periods]

#                     if len(block) == lab_periods:
#                         block_var = model.NewBoolVar(f"lab_block_{section}_{subject_id}_{d}_{start_p}")

#                         # Enforce that when block_var is true, all vars in block are set to 1
#                         for _, _, var in block:
#                             model.Add(var == 1).OnlyEnforceIf(block_var)
#                             model.Add(var == 0).OnlyEnforceIf(block_var.Not())

#                         lab_block_options.append(block_var)

#             # Allow exactly one lab block to be scheduled per week
#             model.Add(sum(lab_block_options) == 1)

# Constraint 6: Labs cannot be scheduled in parallel across sections at the same time (lab resource exclusive)
for d in days:
     for p in range(1, periods_per_day + 1):
         for subj in subjects:
             if subj["subject_type"].lower() == "lab":
                 vars_for_lab = []
                 for section in sections:
                     for (subject_id, faculty_id), (s, fac, var_list) in timetable[section].items():
                         if subject_id == subj["subject_id"]:
                             for (day, period, var) in var_list:
                                 if day == d and period == p:
                                     vars_for_lab.append(var)
                 if vars_for_lab:
                     model.Add(sum(vars_for_lab) <= 1)

solver = cp_model.CpSolver()
status = solver.Solve(model)

print(f"Solver status: {solver.StatusName(status)}")
print(f"Conflicts: {solver.NumConflicts()}")
print(f"Branches: {solver.NumBranches()}")
print(f"Wall time: {solver.WallTime()}s")

if status in [cp_model.OPTIMAL, cp_model.FEASIBLE]:
    result = {}
    for section, assignments in timetable.items():
        result[section] = {d: [""] * periods_per_day for d in days}
        for (subject_id, faculty_id), (subj, fac, var_list) in assignments.items():
            for (d, p, var) in var_list:
                if solver.BooleanValue(var):
                    result[section][d][p - 1] = f"{subj['subject_name']} ({fac['faculty_name']})"
    with open("generated_timetable.json", "w") as f:
        json.dump(result, f, indent=2)
    print("✅ Timetable generated and saved to 'generated_timetable.json'")
else:
    print("❌ No feasible timetable found.")
