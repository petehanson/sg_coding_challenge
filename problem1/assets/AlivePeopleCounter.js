/** 
 * Accepts a list of peoples' birth and death years and calculates the
 * number of people alive during each year between the set's minimum and maximum
 * birth and death years.
 */
var AlivePeopleCounter = function() {
    // Counts the number of people alive during each year
    var years = {};
    
    /**
     * Adds a person to the dataset given their birth year and death year as
     * integers.  Birth year must be less than death year and greater than 0.
     */
    this.addPerson = function(birthYear, deathYear) {
        birthYear = parseInt(birthYear);
        deathYear = parseInt(deathYear);
        
        if (deathYear < birthYear) {
            throw "Death year " + deathYear + " cannot be less than birth year " + birthYear;
        }
        
        if (birthYear < 0) {
            throw "Birth year cannot be less than 0";
        }
        
        for (var year = birthYear; year <= deathYear; year++) {
            years[year] = years[year] ? years[year]+1 : 1;
        }
    };
    
    /**
     * Returns the year with the largest number of people alive.  If multiple
     * years have the same largest number of people alive, the first year is
     * returned.  If no years are defined, returns null.
     */
    this.getYearWithLargestPopulation = function() {
        var largestYear = -1;
        
        for (var year in years) {
            if (largestYear < 0 || years[year] > years[largestYear]) {
                largestYear = year;
            }
        }
        
        if (largestYear >= 0) {
            return largestYear;
        }
        
        return null;
    };
    
    /**
     * Returns the number of people alive during a given year
     */
    this.getPopulationOfYear = function(year) {
        return years[year] ? years[year] : null;
    };
    
    /**
     * Returns a list of the number of people alive during every year between
     * the minimum birth year and the maximum death year.
     */
    this.getYearlyPopulations = function() {
        var minYear = -1;
        var maxYear = -1;
        var results = {};
        
        for (var year in years) {
            if (minYear < 0 || minYear > year) {
                minYear = year;
            }
            
            if (maxYear < 0 || maxYear < year) {
                maxYear = year;
            }
        }
        
        if (minYear >= 0 && maxYear >= 0) {
            for (var year = minYear; year <= maxYear; year++) {
                results[year] = years[year] ? years[year] : 0;
            }
        }
        
        return results;
    };
};