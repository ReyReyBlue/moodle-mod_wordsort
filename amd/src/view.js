export const init = (words, timingmode, timevalue, maxAttempts) => {

    let currentIndex = 0;
    let correctAnswers = 0;
    let wrongAnswers = 0;
    let timerInterval;
    let elapsed = 0;
    let attempts = [];
    let currentAttempt = 1;

    const startScreen = document.getElementById('wordsort-start-screen');
    const activityScreen = document.getElementById('wordsort-activity-screen');

    const resultsScreen = document.getElementById('wordsort-results-screen');
    const resultAttempts = document.getElementById('wordsort-result-attempts');
    const resultAttempt = document.getElementById('wordsort-result-attempt');
    const resultBestScore = document.getElementById('wordsort-result-bestscore');
    const resultScore = document.getElementById('wordsort-result-score');
    const resultTime = document.getElementById('wordsort-result-time');
    const resultButtons = document.getElementById('wordsort-result-buttons');

    const tryAgainButton = document.getElementById('wordsort-tryagain');

    const wordElement = document.getElementById('wordsort-word');
    const timerElement = document.getElementById('wordsort-timer');

    const leftButton = document.querySelector('.wordsort-choice-left');
    const rightButton = document.querySelector('.wordsort-choice-right');

    const startButton = document.getElementById('wordsort-start');
    
    const mode = Number(timingmode);

    if (mode === 1) {
        timerElement.textContent = `Countdown: ${timevalue}`;
    } else if (mode === 2) {
        timerElement.textContent = `Stopwatch: 0`;
    }

function showWord() {

    if (!words.length) {
        return;
    }

    wordElement.textContent = words[currentIndex].word;
}

function startAttempt() {

    currentIndex = 0;
    correctAnswers = 0;
    wrongAnswers = 0;

    clearInterval(timerInterval);

    elapsed = 0;

    if (mode === 1) {

        // Countdown will go here later.

    } else if (mode === 2) {

        timerElement.textContent = 'Stopwatch: 0';

        timerInterval = setInterval(() => {
            elapsed++;
            timerElement.textContent = `Stopwatch: ${elapsed}`;
        }, 1000);
    }

    showWord();
}

function checkAnswer(selectedSide) {

    if (currentIndex >= words.length) {
        return;
    }

    const correct = Number(words[currentIndex].correctside);

    if (selectedSide === correct) {
        correctAnswers++;
    } else {
        wrongAnswers++;
    }

    nextWord();
}

function nextWord() {

    currentIndex++;

    if (currentIndex >= words.length) {
    finishGame();
    return;
}
        showWord();
    }

function saveAttempt(status) {

    attempts.push({
        number: currentAttempt,
        correct: correctAnswers,
        wrong: wrongAnswers,
        unanswered: words.length - (correctAnswers + wrongAnswers),
        time: elapsed,
        status: status
    });

    currentAttempt++;
}    

function getBestAttempt() {

    if (attempts.length === 0) {
        return null;
    }

    let bestAttempt = attempts[0];

    for (let i = 1; i < attempts.length; i++) {

        if (attempts[i].correct > bestAttempt.correct) {
            bestAttempt = attempts[i];
        }
    }

    return bestAttempt;
}

function resetGame() {

    resultsScreen.style.display = 'none';
    activityScreen.style.display = 'block';

    startAttempt();
}

function finishGame() {

    clearInterval(timerInterval);

    saveAttempt('completed');

    activityScreen.style.display = 'none';
    resultsScreen.style.display = 'block';

    resultAttempt.textContent =
    `Attempt: ${currentAttempt - 1} / ${maxAttempts}`;

    resultScore.textContent =
        `Score: ${correctAnswers}/${words.length}`;

    const bestAttempt = getBestAttempt();

    if (bestAttempt) {
        resultBestScore.textContent =
            `Best score: ${bestAttempt.correct}/${words.length}`;
    }

    resultTime.textContent =
        `Time: ${elapsed} seconds`;
}
    
if (!startButton) {
        return;
    }
    startButton.addEventListener('click', () => {

        startScreen.style.display = 'none';
        activityScreen.style.display = 'block';
    startAttempt();    });

if (leftButton) {
    leftButton.addEventListener('click', () => {
        checkAnswer(0);
    });
}

if (rightButton) {
    rightButton.addEventListener('click', () => {
        checkAnswer(1);
    });
}

if (tryAgainButton) {
    tryAgainButton.addEventListener('click', () => {
        resetGame();
    });
}

};