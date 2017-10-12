var requirements_progress = $('div.requirements a.disabled').length / $('div.requirements a').length * 100;
var requirements_bg;

if (requirements_progress < 100) {
    $('div.requirements .description').text('You still need to complete the tasks below before your profile and listings are public.');

    if (requirements_progress < 25) {
        if (requirements_progress == 0) requirements_progress += 1;
        requirements_bg = 'bg-danger';
    } else if (requirements_progress < 50) {
        requirements_bg = 'bg-warning';
    } else if (requirements_progress < 75) {
        requirements_bg = 'bg-info';
    }
} else {
    $('div.requirements .description').text('Looks great! Your profile is active and your available listings are viewable by the public.');
    requirements_bg = 'bg-success';
}

$('div.requirements .progress-bar').css('width', requirements_progress + '%');
$('div.requirements .progress-bar').addClass(requirements_bg);


var goals_progress = $('div.goals a.disabled').length / $('div.goals a').length * 100;
var goals_bg;

if (goals_progress < 100) {
    $('div.goals .description').text('Try to complete the tasks below!');

    if (goals_progress < 25) {
        if (goals_progress == 0) goals_progress += 1;
        goals_bg = 'bg-danger';
    } else if (goals_progress < 50) {
        goals_bg = 'bg-warning';
    } else if (goals_progress < 75) {
        goals_bg = 'bg-info';
    }
} else {
    $('div.goals .description').text('You\'re doing great!');
    goals_bg = 'bg-success';
}

$('div.goals .progress-bar').css('width', goals_progress + '%');
$('div.goals .progress-bar').addClass(goals_bg);