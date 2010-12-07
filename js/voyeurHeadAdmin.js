jQuery(document).ready(function($) {
	// Run the tool initially.
	voyeurUpdateOptions($('#voyeurSettingsTable'), $('#voyeurTool').val());
	$('#voyeurTool').change(function() {
		voyeurUpdateOptions($('#voyeurSettingsTable'), $('#voyeurTool').val());
	});
});

function voyeurUpdateOptions(voyeurSettingsTable, currentTool) {
	var fadeTime = 250;
	if (currentTool == 'Bubbles' || currentTool == 'Reader' || currentTool == 'WordCountFountain') {
		voyeurSettingsTable.find('#removeFuncWords').closest('tr').fadeOut(fadeTime);
	}
	if (currentTool == 'Cirrus') {
		voyeurSettingsTable.find('#removeFuncWords').closest('tr').fadeIn(fadeTime);
	}
	if (currentTool == 'CorpusTypeFrequenciesGrid') {
		voyeurSettingsTable.find('#removeFuncWords').closest('tr').fadeIn(fadeTime);
	}
	if (currentTool == 'Links') {
		voyeurSettingsTable.find('#removeFuncWords').closest('tr').fadeIn(fadeTime);
	}
	if (currentTool == 'CorpusSummary') {
		voyeurSettingsTable.find('#removeFuncWords').closest('tr').fadeIn(fadeTime);
	}
	if (currentTool == 'Cirrus') {
		voyeurSettingsTable.find('#removeFuncWords').closest('tr').fadeIn(fadeTime);
	}
}

// Testing for SSH key...