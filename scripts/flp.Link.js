flp.Link = (function() {
    var Construct = function(settings) {
	    var _this = this,
		    ds = this.settingDefaults,
		    i,
		    icon,
		    phrase,
		    beginning,
		    middle,
		    end,
		    pairs,
		    to;

	    for(i in ds) {
		    if ((settings[i] = settings[i] || ds[i]) === null) {
			    return;
		    }
	    }

	    this.settings = settings;

	    phrase = this.phrase =
		    (beginning = this.settings.beginning)
		    .add(middle = this.settings.middle)
		    .add(end = this.settings.end);

        icon = this.icon = document.createElement('span');
        icon.className = 'futureLink';

	    to = settings.to.toLowerCase();
	    switch (to) {
		    case 'past':
			    beginning.before(icon);
			    break;
		    default:
			    end.after(icon);
	    }

	    pairs = settings.pairs

        icon.onclick = function(){
            var tab = document.createElement('table');
            tab.setAttribute('border', '1px');
            for(var i = 0; i < pairs.length; i++){
                var auth = pairs[i].pair[to].author,
                    prof = pairs[i].pair[to].authorProfession,
                    site = pairs[i].pair[to].href,
                    row1 = document.createElement('tr'),
                    row2 = document.createElement('tr'),
                    thead = document.createElement('thead'),
                    head1 = document.createElement('th'),
                    head2 = document.createElement('th'),
                    head3 = document.createElement('th'),
                    link = document.createElement('a'),
                    col1 = document.createElement('td'),
                    col2 = document.createElement('td'),
                    col3 = document.createElement('td'),
                    tbody = document.createElement('tbody');

                link.setAttribute('href', site);
                head1.textContent = _this.translate('Author');
                head2.textContent = _this.translate('Profession');
                head3.textContent = _this.translate('Source');
                tab.appendChild(thead);
                tab.appendChild(tbody);
                tbody.appendChild(row2);
                thead.appendChild(row1);
                row1.appendChild(head1);
                row1.appendChild(head2);
                row1.appendChild(head3);
                row2.appendChild(col1);
                row2.appendChild(col2);
                row2.appendChild(col3);
                col1.textContent = auth;
                col2.textContent = prof;
                col3.appendChild(link);
                link.textContent = site;
            }

            _this.show(tab);
        };

	    switch(settings.count) {
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


        phrase
	        .addClass('ui-state-highlight')
	        .css('border-width', '0px');
    };

    Construct.prototype = {
        show: function (table) {},
	    translate: function(text) {return text;},
	    settingDefaults: {
		    beginning: null,
		    middle: null,
		    end: null,
		    count: 1,
		    pairs: [],
		    to: 'future'
	    }
    };

    return Construct;
})();