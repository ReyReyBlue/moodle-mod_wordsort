import Ajax from 'core/ajax';
import Notification from 'core/notification';

export const init = (
    wordsortid,
    categoryLeft,
    categoryRight,
    timingmode,
    timevalue,
    maxAttempts,
    shuffleEnabled,
    feedbackMode,

    resultString,
    wordString,
    yourAnswerString,
    correctAnswerString
) => {

    const rawWords = document.getElementById('wordsort-data').dataset.words;

    const words = JSON.parse(rawWords);

    let currentIndex = 0;
    let correctAnswers = 0;
    let wrongAnswers = 0;
    let timerInterval;
    let elapsed = 0;
    let attempts = [];
    let currentAttempt = 1;
    let currentWords = [];
    let currentAnswers = [];
    let currentAttemptId = null;

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
    const submitButton = document.getElementById('wordsort-submit');
    const wordElement = document.getElementById('wordsort-word');
    const timerElement = document.getElementById('wordsort-timer');
    const leftButton = document.querySelector('.wordsort-choice-left');
    const rightButton = document.querySelector('.wordsort-choice-right');
    const startButton = document.getElementById('wordsort-start');
    const feedbackElement = document.getElementById('wordsort-feedback');
    
    const submissionScreen = document.getElementById('wordsort-submission-screen');
    const submissionBestScore = document.getElementById('wordsort-submission-bestscore');
    const submissionAttempts = document.getElementById('wordsort-submission-attempts');
    const submissionTime = document.getElementById('wordsort-submission-time');
    const submissionAnswers = document.getElementById('wordsort-submission-answers');
    const submissionBestAttempt = document.getElementById('wordsort-submission-bestattempt');
            
    const mode = Number(timingmode);    
    const feedback = Number(feedbackMode);
    
    if (mode === 1) {
        timerElement.textContent = `Countdown: ${timevalue}`;
    } else if (mode === 2) {
        timerElement.textContent = `Stopwatch: 0`;
    }

    function shuffleWords(array) {

        for (let i = array.length - 1; i > 0; i--) {

            const j = Math.floor(Math.random() * (i + 1));

            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    function showWord() {

        if (!words.length) {
            return;
        }

        wordElement.textContent = words[currentIndex].word;
    }

    function startAttempt() {

        if (shuffleEnabled) {
            shuffleWords(words);
        }

        currentIndex = 0;
        correctAnswers = 0;
        wrongAnswers = 0;
        currentAnswers = [];

        clearInterval(timerInterval);

        elapsed = 0;
        timerElement.classList.remove('text-danger');

        if (mode === 1) {

            // Countdown.
            elapsed = timevalue;

            timerElement.textContent = `Countdown: ${elapsed}`;
            timerInterval = setInterval(() => {
                elapsed--;
                timerElement.textContent = `Countdown: ${elapsed}`;

                if (elapsed <= 10) {
                    timerElement.classList.add('text-danger');
                } else {
                    timerElement.classList.remove('text-danger');
                }

                if (elapsed <= 0) {
                    clearInterval(timerInterval);
                    finishGame();
                }
            }, 1000);

        } else if (mode === 2) {

            // Stopwatch.
            timerElement.textContent = 'Stopwatch: 0';
            timerInterval = setInterval(() => {
                elapsed++;
                timerElement.textContent = `Stopwatch: ${elapsed}`;

                if (elapsed > timevalue) {
                    timerElement.classList.add('text-danger');
                } else {
                    timerElement.classList.remove('text-danger');
                }
            }, 1000);
        }

        showWord();
    }

    function checkAnswer(selectedSide) {

        if (currentIndex >= words.length) {
            return;
        }

        currentAnswers.push({
            word: words[currentIndex].word,
            selected: selectedSide,
            correct: Number(words[currentIndex].correctside)
        });

        const correct = Number(words[currentIndex].correctside);

        if (selectedSide === correct) {
            correctAnswers++;
        } else {
            wrongAnswers++;
        }

        if (feedback === 1) {
           showFeedback(selectedSide === correct);
        } else {
            nextWord();
        }
    }

    function showFeedback(isCorrect) {

        feedbackElement.textContent =
            isCorrect ? "✅ Correct" : "❌ Incorrect";

        setTimeout(() => {

            feedbackElement.textContent = "";
            nextWord();

        }, 1000);
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
            status: status,
            answers: [...currentAnswers]
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
        activityScreen.style.display = 'none';
        submissionScreen.style.display = 'none';

        startScreen.style.display = 'block';
    }
    
    function finishGame() {

        clearInterval(timerInterval);

        saveAttempt('completed');

        activityScreen.style.display = 'none';
        resultsScreen.style.display = 'block';

        resultAttempt.textContent =
        `Attempt: ${currentAttempt - 1} / ${maxAttempts}`;

        if (currentAttempt > maxAttempts) {
            tryAgainButton.style.display = 'none';
        } else {
            tryAgainButton.style.display = 'inline-block';
        }

        resultScore.textContent =
            `Score: ${correctAnswers}/${words.length}`;

        const bestAttempt = getBestAttempt();

        if (bestAttempt) {
            resultBestScore.textContent =
                `Best score: ${bestAttempt.correct}/${words.length}`;
        }

        if (mode === 0) {
            resultTime.style.display = 'none';
        } else {
            resultTime.style.display = 'block';

            if (mode === 1) {

                // Countdown: show time used.
                const timeused = timevalue - elapsed;

                resultTime.textContent = `Time: ${timeused} seconds`;

            } else if (mode === 2) {

                // Stopwatch: show elapsed time.
                resultTime.textContent = `Time: ${elapsed} seconds`;
            }
        }
    }

    // ----------------------
    // Submission
    // ----------------------

    function submitActivity() {
        submitButton.disabled = true;
        const bestAttempt = getBestAttempt();

        Ajax.call([{
            methodname: 'mod_wordsort_save_attempt',
            args: {
                attemptid: currentAttemptId,
                wordsortid: wordsortid,
                score: bestAttempt.correct,
                totalwords: words.length,
                percentage: (bestAttempt.correct / words.length) * 100,
                timeused: bestAttempt.time,
                answers: JSON.stringify(bestAttempt.answers)
            }
        }])[0].then(result => {
        })
        .catch(error => {
        });

        if (Number(feedbackMode) === 2) {
            showSubmissionSummary(bestAttempt);
            renderSubmissionAnswers(bestAttempt.answers);

            resultsScreen.style.display = 'none';
            submissionScreen.style.display = 'block';
        } else {
            // Finish activity without submission review.
        }
    }

    function showSubmissionSummary(bestAttempt) {

        submissionBestScore.textContent =
            `Best score: ${bestAttempt.correct}/${words.length}`;

        submissionAttempts.textContent =
            `Attempts used: ${attempts.length}/${maxAttempts}`;
        
        submissionBestAttempt.textContent =
            `Best attempt: ${bestAttempt.number}/${attempts.length}`;

        if (Number(timingmode) === 0) {
            submissionTime.style.display = 'none';
        } else {
            submissionTime.style.display = '';
            submissionTime.textContent =
                `Time: ${bestAttempt.time} seconds`;
        }
    }

    function renderSubmissionAnswers(answers) {

        let html = `
            <table class="generaltable wordsort-review-table">
                <thead>
                    <tr>
                        <th>${resultString}</th>
                        <th>${wordString}</th>
                        <th>${yourAnswerString}</th>
                        <th>${correctAnswerString}</th>
                    </tr>
                </thead>
                <tbody>
        `;

        answers.forEach(answer => {

            const result =
                answer.selected === answer.correct ? '✅' : '❌';
            const selectedText =
                Number(answer.selected) === 0 ? categoryLeft : categoryRight;

            const correctText =
                Number(answer.correct) === 0 ? categoryLeft : categoryRight;

            html += `
                <tr>
                    <td>${result}</td>
                    <td>${answer.word}</td>
                    <td>${selectedText}</td>
                    <td>${correctText}</td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
        `;

        submissionAnswers.innerHTML = html;
    }
        

    // ----------------------
    // Event listeners
    // ----------------------
                
        if (!startButton) {
            return;
        }

        startButton.addEventListener('click', () => {

            Ajax.call([{
                methodname: 'mod_wordsort_start_attempt',
                args: {
                    wordsortid: wordsortid
                }
            }])[0].then(result => {

                currentAttemptId = result.attemptid;
                currentAttempt = result.attemptnumber;

                startScreen.style.display = 'none';
                activityScreen.style.display = 'block';

                startAttempt();

            }).catch(Notification.exception);

        });

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

        if (submitButton) {
            submitButton.addEventListener('click', () => {
                submitActivity();
            });
        }
}