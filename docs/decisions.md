# Technical Decisions

## 2026-07-16

### Plugin type

Decision:
Use a Moodle Activity Module (`mod`) instead of a Question Type.

Reason:
The activity requires:
- a continuous timer,
- configurable category labels,
- its own attempt logic,
- custom teacher reports.

These features fit an Activity Module better than a Quiz question type.

# Design decisions

## 2026-07-17

### Activity Module

Decisions:
Develop Word Sort as a Moodle Activity Module.

Reason:
The activity requires its own timing, attempts, reports and grading.

---

### Two Categories

Decisions:
Version 1 supports exactly two categories.

Reason:
This satifies the project requirements and keeps the interface simple.

---

### Prompt-based Design

Decision:
Store prompts instead of only words.

Reason:
Future versions may support phrases, images or audio.

---

### languages

Decision:
Support English and Estonian.

Reason:
The plugin will be used by an Estonian college

## 2026-07-20

Decisions:
Instead of teacher set timer counting down and ending the "game" I will add multible timing options, so teacher can choose what method suits him/her the best.

Reason:
To give teacher a option to grade a students depending time and correct answers - not just time. To be more fair for the students.