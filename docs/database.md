# Word Sort Database Design

## Main Tables

### wordsort

Stores the activity settings.

Fields:

- id
- course
- name
- intro
- introformat
- category1
- category2
- timelimit
- maxattempts
- shuffleitems
- immediatefeedback
- timecreated
- timemodified

---

Fiels:

- activity name
- category labels
- time limit
- attempts
- shuffle
- feedback
- created date
- modified date

### wordsort_items

Stores the words or phrases.

Fields:

- wordsortid
- prompt
- itemtext
- itemimage
- correct category
- sortorder
- display order

---

### wordsort_attempts

Stores student attempts.

Fields:

- student id
- attempt number
- wordsortid
- userid
- attemptnumber
- timestart
- timefinish
- score

---

### wordsort_answers

Stores every answer.

Fields:

- id
- attemptid
- itemid
- chosencategory
- correct
- timeanswered
- prompt