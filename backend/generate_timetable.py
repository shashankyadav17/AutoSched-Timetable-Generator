import json
import random
from ortools.sat.python import cp_model

# Load input
with open("timetable_input_all_semesters.json", "r") as f:
    data = json.load(f)

model = cp_model.CpModel()
timetable = {}

# Generate variables
for sem_key, sem_data in data["semesters"].items():
    general = sem_data["general_settings"]
    subjects = sem_data["subjects"]
    faculty_allotments = sem_data["faculty_allotments"]

    # Shuffle to introduce random order
    random.shuffle(subjects)
    random.shuffle(faculty_allotments)

    days = general["working_days"]
    periods_per_day = general["periods_per_day"]

    sections = sorted(set(f["section"] for f in faculty_allotments))
    timetable[sem_key] = {}

    for section in sections:
        timetable[sem_key][section] = {}
        for subj in subjects:
            for fac in faculty_allotments:
                if fac["subject_id"] == subj["subject_id"] and fac["section"] == section:
                    var_list = []
                    for d in days:
                        for p in range(1, periods_per_day + 1):
                            var = model.NewBoolVar(f"{sem_key}_{section}_{subj['subject_name']}_{d}_{p}")
                            var_list.append((d, p, var))
                    timetable[sem_key][section][(subj["subject_id"], fac["faculty_id"])] = (subj, fac, var_list)

# Constraints
for sem_key, sem_data in timetable.items():
    general = data["semesters"][sem_key]["general_settings"]
    days = general["working_days"]
    periods_per_day = general["periods_per_day"]

    for section, subject_assignments in sem_data.items():
        for d in days:
            for p in range(1, periods_per_day + 1):
                vars_in_slot = []
                for (subject_id, faculty_id), (_, _, var_list) in subject_assignments.items():
                    for (day, period, var) in var_list:
                        if day == d and period == p:
                            vars_in_slot.append(var)
                model.Add(sum(vars_in_slot) <= 1)

        for (subject_id, faculty_id), (subj, fac, var_list) in subject_assignments.items():
            model.Add(sum([v for _, _, v in var_list]) == subj["lectures_per_week"])
            for d in days:
                model.Add(sum([v for day, _, v in var_list if day == d]) <= 2)

# Cross-semester faculty conflicts
all_days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
for d in all_days:
    for p in range(1, 10):
        faculty_slots = {}
        for sem_key in timetable:
            for section in timetable[sem_key]:
                for (subject_id, faculty_id), (_, _, var_list) in timetable[sem_key][section].items():
                    for (day, period, var) in var_list:
                        if day == d and period == p:
                            faculty_slots.setdefault(faculty_id, []).append(var)
        for fid, vars_list in faculty_slots.items():
            model.Add(sum(vars_list) <= 1)

# Solve
solver = cp_model.CpSolver()

# ADD THESE PARAMETERS for real randomness
solver.parameters.random_seed = random.randint(1, 1000000)
solver.parameters.search_branching = cp_model.PORTFOLIO_SEARCH
# solver.parameters.solution_limit = 1  # Removed due to AttributeError

status = solver.Solve(model)

print(f"Status: {solver.StatusName(status)}")
print(f"Conflicts: {solver.NumConflicts()}")
print(f"Branches: {solver.NumBranches()}")
print(f"WallTime: {solver.WallTime()}s")

if status in [cp_model.OPTIMAL, cp_model.FEASIBLE]:
    result = {}
    all_days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
    for sem_key in timetable:
        result[sem_key] = {}
        general = data["semesters"][sem_key]["general_settings"]
        working_days = general["working_days"]
        periods = general["periods_per_day"]

        for section, subject_assignments in timetable[sem_key].items():
            # Initialize all days with empty slots
            result[sem_key][section] = {d: [""] * periods for d in all_days}
            # Fill only working days with scheduled classes
            for (subject_id, faculty_id), (subj, fac, var_list) in subject_assignments.items():
                for d, p, var in var_list:
                    if solver.BooleanValue(var):
                        result[sem_key][section][d][p - 1] = f"{subj['subject_name']} ({fac['faculty_name']})"

    with open("generated_timetable_all.json", "w") as f:
        json.dump(result, f, indent=2)
    print("Saved to generated_timetable_all.json")
else:
    print("❌ No feasible solution found.")
