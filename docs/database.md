# Word Sort Database Design

## Main Tables

### wordsort

Stores the activity settings.

wordsort
---------
id
course
name
intro
timingmode
timevalue
maxattempts
shufflewords
feedbackmode
...

wordsort_categories
-------------------
id
wordsortid
name
description
sortorder

wordsort_words
--------------
id
wordsortid
word
categoryid
sortorder

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