/**
 * Displays a random set of data containing count people with birth and death
 * dates between minYear and maxYear.
 */
function randomize(count, minYear, maxYear) {
    var results = [];
    
    for (var entry = 0; entry < count; entry++) {
        var birth = Math.floor(Math.random() * (maxYear - minYear + 1)) + minYear;
        var death = Math.floor(Math.random() * (maxYear - birth + 1)) + birth;
        results.push([birth, death]);
    }
    
    updateUI(results);
}

/**
 * Calculates the final dataset and draws the UI
 */
function updateUI(dataset) {
    var counter = new AlivePeopleCounter;

    try {
        for (var index in dataset) {
            counter.addPerson(dataset[index][0], dataset[index][1]);
        }
    } catch(ex) {
        alert(ex);
        return;
    }

    var largestYear = counter.getYearWithLargestPopulation();
    var largestPop = counter.getPopulationOfYear(largestYear);

    $("#result").text("First year with greatest number of people alive: " + largestYear + " (" + largestPop + " people)");

    var populations = counter.getYearlyPopulations();
    var graphData = [];

    for(var index in populations) {
        graphData.push([index, populations[index]]);
    }

    $.plot($("#result-graph"), [graphData]);
}

// Draw a static dataset initially
updateUI(dataset);

// Generate a random dataset of 1000 people with birth/death dates between
// 1900 and 2000 when the randomize button is pressed.
$("#randomize").bind('click', function() {
   randomize(1000, 1900, 2000); 
});