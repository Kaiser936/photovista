document.addEventListener('DOMContentLoaded', function () {
	var hiddenDates = document.querySelectorAll('.hidden-date');
	hiddenDates.forEach(function (element) {
		var createdAt = parseInt(element.textContent);
		var currentDate = Math.floor(Date.now() / 1000); // Date actuelle en secondes UNIX

		var timeDiff = currentDate - createdAt;
		var timeText = '';

		if (timeDiff < 60) {
			timeText = 'il y a ' + timeDiff + ' secondes';
		} else if (timeDiff < 3600) {
			var minutes = Math.floor(timeDiff / 60);
			timeText = 'il y a ' + minutes + ' minutes';
		} else if (timeDiff < 86400) {
			var hours = Math.floor(timeDiff / 3600);
			timeText = 'il y a ' + hours + ' heures';
		} else {
			var days = Math.floor(timeDiff / 86400);
			timeText = 'il y a ' + days + ' jours';
		}

		element.textContent = timeText;
	});
});