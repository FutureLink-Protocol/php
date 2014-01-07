var FutureLink = function(beginning, middle, end) {
    var phrase = this.phrase = beginning.add(middle).add(end);
    this.beginning = beginning;
    this.middle = middle;
    this.end = end;

    phrase.css('background-color', 'yellow');
};