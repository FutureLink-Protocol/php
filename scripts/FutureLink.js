var FutureLink = (function() {

    var Construct = function(beginning, middle, end, count, phrases) {
        var that = this,
            phrase = this.phrase = beginning.add(middle).add(end);
        this.beginning = beginning;
        this.middle = middle;
        this.end = end;
        this.count = count;
        this.phrases = phrases;

        var icon = this.icon = document.createElement('span');
        icon.className = 'futureLink';
        this.end.after(icon);
        icon.onclick = function(){
            var tab = document.createElement('table');
            tab.setAttribute('border', '1px');
            for(var i = 0; i < phrases.length; i++){
                var auth = phrases[i].pair.past.author,
                    prof = phrases[i].pair.past.authorProfession,
                    site = phrases[i].pair.past.href,
                    row1 = document.createElement('tr'),
                    row2 = document.createElement('tr'),
                    thead = document.createElement('thead'),
                    head1 = document.createElement('th'),
                    head2 = document.createElement('th'),
                    head3 = document.createElement('th'),
                    link = document.createElement('a'),
                    col1 = document.createElement('td'),
                    col2 = document.createElement('td'),
                    col3 = document.createElement('td');
                link.setAttribute('href', String(site));
                head1.innerHTML = 'Author';
                head2.innerHTML = 'Profession';
                head3.innerHTML = 'Source';
                tab.appendChild(thead);
                tab.appendChild(row2);
                thead.appendChild(row1);
                row1.appendChild(head1);
                row1.appendChild(head2);
                row1.appendChild(head3);
                row2.appendChild(col1);
                row2.appendChild(col2);
                row2.appendChild(col3);
                col1.innerText = auth;
                col2.innerText = prof;
                col3.appendChild(link);
                link.innerText = site;
            }

            that.show(tab);
        };
        switch(count){
            case 1:
                icon.innerHTML = '&#9676;';
                break;
            case 2:
                icon.innerHTML = '&#9675;';
                break;
            case 3:
                icon.innerHTML = '&#9678;';
                break;
            case 4:
                icon.innerHTML = '&#9673;';
                break;
            default:
                icon.innerHTML = '&#9679;';
                break;
        }
        phrase.addClass('ui-state-highlight').css('border-width', '0px');
    }

    Construct.prototype = {
        show: function (table) {}
    };

    return Construct;
})();