**Version:** 0.1

**Status:** In Development

**Last updated:** 2026-07-16


# Word Sort - Requirements Specification

## Project Information

**Project:** Word Sort Moodle Activity Module

**Plugin type:** Activity Module (`mod`)

**Developer:** Reelika Pihl

**Development environment:** Moodle 4.5+, Docker, Visual Studio Code, Git

---

# 1. Project Purpose

The purpose of the Word Sort activity is to provide teachers with an interactive learning activity where students quickly categorize words or phrases into one of two configurable categories.

The activity is intended to support fast recognition, memorization, and learning through repeated practice under time pressure.

---

# 2. Functional Requirements

## Teacher

The teacher shall be able to:

- Create a new Word Sort activity.
- Give the activity a name.
- Configure the first category label.
- Configure the second category label.
- Set a time limit.
- Set the maximum number of attempts.
- Enable or disable random order of items.
- Enable or disable immediate feedback after each answer.
- Add words or phrases together with the correct category.
- View student attempts.
- View student scores.
- View detailed student answers.

---

## Student

The student shall be able to:

- Open the activity.
- Read the instructions.
- Start an attempt.
- See one word or phrase at a time.
- Select one of the two categories.
- Continue automatically to the next item.
- Complete the activity before the timer expires.
- View the final score after completion.

---

# 3. System Requirements

The plugin shall:

- Run on Moodle 4.5 or newer.
- Store all attempts in the Moodle database.
- Integrate with Moodle Gradebook.
- Support multiple attempts.
- Support English and Estonian language packs.
- Work on desktop and mobile browsers.

---

# 4. Teacher Configuration

Each activity shall allow configuration of:

- Activity name
- Description
- Category 1 label
- Category 2 label
- Time limit
- Maximum attempts
- Shuffle items
- Immediate feedback

---

# 5. Student Workflow

1. Student opens activity.
2. Student presses Start.
3. Timer begins.
4. One word is displayed.
5. Student chooses Category 1 or Category 2.
6. Answer is saved.
7. Next word appears immediately.
8. Repeat until:
   - all items are answered, or
   - the timer expires.
9. Final score is displayed.

---

# 6. Teacher Reports

The teacher shall be able to view:

- Student name
- Attempt number
- Date
- Time used
- Final score
- Individual answers
- Correct and incorrect responses

---

# 7. Future Enhancements

Possible future improvements include:

- Image support
- Audio support
- CSV import
- More than two categories
- Question statistics
- Difficulty levels
- Random item selection
- Progress bar
- Keyboard shortcuts


---
Document Version: 0.1

Last Updated: 2026-07-16