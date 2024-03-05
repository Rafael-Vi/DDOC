const targetDate = new Date(targetDateFromPHP);

function calculateRemainingTime() {
  const currentDate = new Date();
  const remainingTime = targetDate - currentDate;

  // Calculate remaining days, hours, minutes, and seconds
  const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
  const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

  return { days, hours, minutes, seconds };
}

function startTimer() {
  const timerInterval = setInterval(() => {
    const remainingTime = calculateRemainingTime();

    // Check if the target date has been reached
    if (remainingTime.days <= 0 && remainingTime.hours <= 0 && remainingTime.minutes <= 0 && remainingTime.seconds <= 0) {
      clearInterval(timerInterval);
      console.log("Target date reached!");
    } else {
      console.log(`Time remaining: ${remainingTime.days} days, ${remainingTime.hours} hours, ${remainingTime.minutes} minutes, ${remainingTime.seconds} seconds`);
    }
  }, 1000); // Update every second
}

startTimer();