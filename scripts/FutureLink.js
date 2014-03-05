var FutureLink = function(beginning, middle, end, count) {
    var phrase = this.phrase = beginning.add(middle).add(end);
    this.beginning = beginning;
    this.middle = middle;
    this.end = end;
    this.count = count;

    phrase.css('background-color', 'yellow');
    $("<span>&amp;</span>").insertAfter(this.end);
};