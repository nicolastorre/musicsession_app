var score, totalnote, draw, svgGraph;

$(document).ready(function() {

document.getElementById('title').value = scoretitle;
document.getElementById('composer').value = scorecomposer;
document.getElementById('category').value = scorecategory;

var nbbeats, beatunit, totalbar, nstaff, noteduration, type, typeunit, currentBar, type, noteref;

$('#next').on('click', function(event) {
	event.preventDefault();
	var popup = document.getElementById('popup-music');	

	nbbeats = parseInt(document.getElementById('nbbeats').value);
	beatunit  = parseInt(document.getElementById('beats').value);

	if(nbbeats > 0 && beatunit > 0) {
		popup.style.display = "none";

		// Init the SVG drawing
		draw = SVG('drawing').size(1000,1000);
		svgGraph = document.getElementById('drawing');

		// Init the working layer
		score = new Object();

		totalnote = 0;
		totalnotestaff = 0;
		totalbar = 0;
		nstaff = 0;

		noteduration = [];
		type = ["eighth","quarter","half","whole"];
		typeunit = [8,4,2,1];
		for (var i=0; i<type.length; i++) {
			noteduration[type[i]] = beatunit/typeunit[i];
		}

		currentBar = 0;
		type = "quarter";
		initRefStepPosOctave();

		var title = draw.text(scoretitle);
		title.font({family: 'Arial', size: 40, anchor: 'middle'})
		title.move(450,50);

		var composer = draw.text(scorecomposer);
		composer.font({family: 'Arial', size: 18, anchor: 'middle'})
		composer.move(900,100);

		newStaff(nstaff);
		
		// Init the GUI event
		svgGraph.addEventListener("click",addNote, true);

		// reference of the midi value note
		n = ["c","cs","d","ds","e","f","fs","g","gs","a","as","b"];
		value = 0;
		noteref = {};
		for (i=0; i<=10; i++) {
			for (var j=0; j<=11; j++) {
				noteref[n[j]+i] = value;
				value++;
			}
		}
		console.log(JSON.stringify(noteref));
	}
});

/*************************************/

function roundMult(y) {
	var md1=5;
	var md2=10;
	var rmd1=y%md1;
	var rmd2=y%md2;

	if (rmd1<rmd2) {
		return ((y+rmd1<y-rmd1)? y+rmd1:y-rmd1);
	} else {
		return ((y+rmd2<y-rmd2)? y+rmd2:y-rmd2);
	}
}

function drawStaff(nstaff) {
	var group = draw.group();
	for(var i=0;i<5;i++) {
		var idline = "line_"+i;
		group.add(draw.line(0,200+(nstaff*200)+(i*10),1000,200+(nstaff*200)+(i*10)).stroke({ width: 2}).attr("id",idline));
	}
	group.attr("id","staff_"+nstaff);
}

function resizeStaff() {
	for (var i=0;i<5;i++) {
		id = "line_"+i;
		line = SVG.get(id);
		var size =  parseInt(line.attr("x2"))+80;
		line.size(size);
	}
	var drawsize = parseInt(draw.attr("width"));
	if (size + 100 >= drawsize) {
		var drawsize =  drawsize + 1000;
		draw.size(drawsize,400);
	}
// SVG.get("line_0").size(10);
}

function drawKey(nstaff) {
	var group = draw.group();
	var path = draw.path("m 574.17775,584.66155 c -43.4619,-3.7084 -66.54264,-59.2447 -45.06289,-95.27747 22.74455,-53.93846 103.64793,-71.21589 143.9806,-27.01883 32.38112,31.67509 35.15474,85.81353 9.12534,122.24264 -33.67584,52.30516 -109.12229,62.45619 -160.92393,32.45961 -40.75374,-21.68948 -67.88426,-67.06068 -64.19585,-113.57443 2.21708,-56.53461 37.67501,-105.52834 79.46428,-140.9409 34.6461,-34.1562 73.91884,-65.68009 98.24838,-108.48927 9.10338,-22.13655 24.53051,-67.44061 -6.9888,-76.85245 -25.66701,7.00058 -31.79687,37.01121 -44.33944,57.07464 -14.48366,27.3573 -19.89473,59.83429 -10.76293,89.78229 20.23519,128.14273 37.75644,256.718 54.23778,385.38239 2.61681,35.45287 -24.0997,72.85163 -61.38415,73.66831 -33.22302,3.27258 -72.65056,-23.50892 -66.92402,-59.93191 4.85046,-29.95367 50.02615,-44.94205 67.92748,-17.63743 24.60921,22.68985 -6.23295,55.43526 -13.67931,67.24137 20.60572,11.73569 44.1145,-6.59605 55.36544,-23.60496 11.31474,-23.27636 6.43833,-50.59354 5.63136,-75.6343 -9.17273,-103.95439 -26.67551,-206.94945 -40.7071,-310.30872 -5.87474,-41.93441 -27.70664,-82.81447 -18.52694,-125.86625 6.13556,-28.47731 22.8121,-53.8142 41.64493,-75.54485 18.03413,-24.52796 57.92748,-18.26316 64.07472,12.88206 16.45753,56.87841 -8.64372,115.72642 -43.65554,160.12134 -34.60385,47.12266 -80.37266,85.02584 -112.80932,133.75447 -37.67102,50.65158 -10.21099,135.55763 52.57462,149.97885 38.92767,12.94283 92.07674,-2.39598 103.49168,-45.68336 11.49381,-33.64568 0.095,-79.6085 -36.88127,-91.41487 -37.69043,-12.95128 -81.81656,20.4118 -78.42551,60.52157 1.17824,18.3404 15.55156,32.41753 29.50039,42.67046 z");
	path.size(30);
	path.move(10,200+(200*nstaff)-25);
	group.add(path);
	group.attr("id","key_"+nstaff);
}

function drawTimeSign(nstaff) {
	var group = draw.group();
	var t1 = draw.text(String(nbbeats));
	t1.font({family:   'Arial', size:     28, anchor:   'middle', leading: 0})
	t1.move(70,200+(200*nstaff)-5);
	var t2 = draw.text(String(beatunit));
	t2.font({family:   'Arial', size:     28, anchor:   'middle', leading: 0})
	t2.move(70,200+(200*nstaff)+15);
	group.add(t1);
	group.add(t2);
	group.attr("id","time_"+nstaff);
}
		


function drawBarLine(x,nstaff) {
	var group = draw.group();
	group.add(draw.line(x,200+(nstaff*200),x,240+(nstaff*200)).stroke({ width: 2}));
	group.attr("id","bar_"+totalbar);
}

function drawNote(type ,mouseX ,mouseY,id ) {
	var group = draw.group();
	switch (type) {
		case "eighth":
			group.add(draw.circle(11).attr({ cx: mouseX, cy: mouseY }));
			group.add(draw.line(mouseX + 5, mouseY, mouseX + 5, mouseY - 30).stroke({ width: 2 }));
			group.add(draw.line(mouseX + 5, mouseY - 30, mouseX + 15, mouseY - 15).stroke({ width: 2 }));
			break;
		case "quarter":
			group.add(draw.circle(11).attr({ cx: mouseX, cy: mouseY }));
			group.add(draw.line(mouseX + 5, mouseY, mouseX + 5, mouseY - 30).stroke({ width: 2 }));
			break;
		case "half":
			group.add(draw.circle(11).attr({ cx: mouseX, cy: mouseY }).fill({ color: '#f06', opacity: 0.0 }).stroke({color: '#000', width: 2}));
			group.add(draw.line(mouseX + 5, mouseY, mouseX + 5, mouseY - 30).stroke({ width: 2 }));
			break;
		case "whole":
			group.add(draw.circle(11).attr({ cx: mouseX, cy: mouseY }).fill({ color: '#f06', opacity: 0.0 }).stroke({color: '#000', width: 2}));
	}
	if (score["note_"+totalnote].pos >= 0 && score["note_"+totalnote].pos <= 40) {
		for (var i = 40; i >= score["note_"+totalnote].pos; i -= 10) {
			group.add(draw.line(mouseX - 7, i + stafflimittop, mouseX + 7, i+stafflimittop).stroke({ width: 2 }));
		}
	}
	if (score["note_"+totalnote].pos >= 100 && score["note_"+totalnote].pos <= 195) {
		for (var i = 100; i <= score["note_"+totalnote].pos; i += 10) {
			group.add(draw.line(mouseX - 7, i + stafflimittop, mouseX + 7, i+stafflimittop).stroke({ width: 2 }));
		}
	}
	group.attr("id","note_"+id);
}

function setTypeNote(val) {
	type = val;
}

function addNote(evt) {
	var bRect = svgGraph.getBoundingClientRect();
	var mouseX_raw = (evt.clientX - bRect.left);
	var mouseY_raw = (evt.clientY-bRect.top);

	mouseX = 100 + (totalnotestaff*20);
	mouseY = roundMult(mouseY_raw);

	if ( mouseY>=stafflimittop && mouseY<stafflimitbot) { //mouseX<mouseX_raw &&
		var newpos = mouseY-stafflimittop-(0*1);

		var n = refPos.indexOf(newpos);

		if (currentBar+noteduration[type] > nbbeats) {
			alert('beats over!');
			return false;
		}

		idnote = "note_"+totalnote;
		score[idnote] = {pos:newpos, step: refStep[n%7], octave: refOctave[n], duration: noteduration[type], type: type, midi: noteref[refStep[n%7]+refOctave[n]]};
		drawNote(type, mouseX, mouseY, totalnote);
		totalnote++;
		totalnotestaff++;
		currentBar += score[idnote].duration;
		staffnew = 0;

		//tmp
		console.log(score[idnote]);
		
		if (currentBar >= nbbeats) {
			totalbar++;
			drawBarLine(mouseX+10,nstaff);
			currentBar = 0;
			//resizeStaff();
		}

		if(mouseX>950) {
			nstaff++;
			newStaff();
			staffnew = 1;
			totalnotestaff = 0;
		} 
	}
	console.log("currentBar: "+currentBar);
}

function removeNote() {
	if(totalnote>0) {
		if(totalnote>0) {
			totalnote--;
		}
		if (totalnotestaff>0) {
			totalnotestaff--;
		}
		var currentnote = SVG.get("note_"+totalnote);
		if (staffnew == 1 && totalnotestaff == 0 && nstaff > 0) {
			SVG.get("staff_"+nstaff).remove();
			SVG.get("key_"+nstaff).remove();
			SVG.get("time_"+nstaff).remove();
			nstaff--;
			SVG.get("bar_"+totalbar).remove();
			currentBar = nbbeats-score["note_"+totalnote].duration;
			totalbar--;
			currentnote.remove();
			stafflimittop -= 200;
			stafflimitbot -= 200;
			totalnotestaff = totalnote;
		}
		else if(currentBar == 0 && totalnote > 0) {
			SVG.get("bar_"+totalbar).remove();
			currentBar = nbbeats-score["note_"+totalnote].duration;
			totalbar--;
			currentnote.remove();
		} 
		else if (totalnote >= 0) {
			currentBar -= score["note_"+totalnote].duration;
			currentnote.remove();
		}
		if (totalnotestaff == 0){
			staffnew = 1;
		}
	}
}

// Init the variable to analyse the note add in the score
function initRefStepPosOctave() {
	refStep = ["b","a","g","f","e","d","c"];
	refPos = new Array();
	refOctave = new Array();
	k=0;
	oct = 6;
	for (var i=0;i<41;i++) {
		refPos[refPos.length] = k;
		refOctave[refOctave.length] = oct;
		k += 5;
		if ((i+1)%7==0) {
			oct--;
		}
	}
}

function newStaff() {
	stafflimittop = 150 + (200*nstaff);
	stafflimitbot = 350 + (200*nstaff);
	drawStaff(nstaff);
	drawKey(nstaff);
	drawTimeSign(nstaff);
}


document.getElementById("eighthbut").onclick = function() {
		setTypeNote('eighth');
		// notebutton = document.getElementsByClassName('notebutton');
		// alert(notebutton.length);
		// for(var i=0; i < notebutton.length; i++){
		// 	console.log(notebutton[i]);
		// 	if(!notebutton[i].hasClass('noteoff')) {
		// 		notebutton[i].classList.add('noteoff');
		// 		notebutton[i].classList.remove('noteon');
		// 	}
		// }
		// $(".notebutton").each(function() {
		// 	if(!($(this).hasClass('noteoff'))) {
		// 		$(this).classList.add('noteoff');
		// 		$(this).classList.remove('noteon');
		// 	}
		// });
		// $('#eighthbut').classList.add('noteon');
	};
document.getElementById("quarterbut").onclick = function() {
		setTypeNote('quarter');
	};
document.getElementById("halfbut").onclick = function() {
		setTypeNote('half');
	};
document.getElementById("wholebut").onclick = function() {
		setTypeNote('whole');
	};

document.getElementById("remove").onclick = function() {
		removeNote();
	};

	$("#download").on('click', function(event) {
	    event.preventDefault();
	    scoredata = $("#drawing").html();
	    $.ajax({
			type: "POST",
			url: "musiceditor/scorepdf/",
			async: true,
			data: [{"name":"score","value": scoredata}, {"name": "title", "value": scoretitle+".pdf"}],
			success: function(data){
			  console.log(data);
			  window.location = "musiceditor/downloadscore/"+scoretitle;
			  return true;
			},
			complete: function() {},
			error: function(xhr, textStatus, errorThrown) {
			 console.log('ajax loading error...');
			 return false;
			}
		});
	});

	$("#save").on('click', function(event) {
	    event.preventDefault();
	    scoredata = $("#drawing").html();
	    $.ajax({
			type: "POST",
			url: "musiceditor/scorepdf/",
			async: true,
			data: [{"name":"score","value": scoredata}, {"name": "title", "value": scoretitle+".pdf"}],
			success: function(data){
				console.log(data);
				$('<form action="musiceditor/savepdftune/" method="POST">')
					.append($('<input type="hidden" name="title" value="' + scoretitle + '">'))
					.append($('<input type="hidden" name="composer" value="' + scorecomposer + '">'))
					.append($('<input type="hidden" name="category" value="' + scorecategory + '">'))
					.append($('<input type="hidden" name="pdf" value="' + scoretitle + ".pdf" + '">'))
					.appendTo($(document.body)) //it has to be added somewhere into the <body>
					.submit();
				return true;
			},
			complete: function() {},
			error: function(xhr, textStatus, errorThrown) {
			 console.log('ajax loading error...');
			 return false;
			}
		});
	});

	$("#downloadmidi").on('click', function(event) {
	    event.preventDefault();
	    $.ajax({
			type: "POST",
			url: "musiceditor/createmidi/",
			async: true,
			data: [{"name":"score","value": JSON.stringify(score)}, {"name": "title", "value": scoretitle}],
			success: function(data){
			  console.log("ok");
			  window.location = "musiceditor/downloadmidi/"+scoretitle;
			  return true;
			},
			complete: function() {},
			error: function(xhr, textStatus, errorThrown) {
			 console.log('ajax loading error...');
			 return false;
			}
		});
	});
});