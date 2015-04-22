function player() {
	var delay = 1; // play one note every quarter second
	var note = 50; // the MIDI note
	var velocity = 127; // how hard the note hits
	for(var i=0; i < totalnote; i++) {
		console.log(delay);
		idnote = "note_" + i;
		MIDI.noteOn(0, score[idnote].midi, velocity, delay);
		delay = delay + score[idnote].duration/2;
		MIDI.noteOff(0, score[idnote].midi, delay);
	}
}

function play() {
	MIDI.loadPlugin({
		soundfontUrl: "Ressources/public/js/midi-js-soundfonts/FluidR3_GM/",
		instrument: "acoustic_grand_piano",
		onprogress: function(state, progress) {
			console.log(state, progress);
		},
		onsuccess: function() {
			var delay = 0; // play one note every quarter second
			var note = 50; // the MIDI note
			var velocity = 127; // how hard the note hits
			// play the note
			player();
		}
	});
}