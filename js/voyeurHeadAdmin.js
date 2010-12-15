jQuery(document).ready(function($) {
	// Run the tool initially.
	voyeurUpdateOptions($('#voyeurSettingsTable'), $('#voyeurTool').val());
	$('#voyeurTool').change(function() {
		voyeurUpdateOptions($('#voyeurSettingsTable'), $('#voyeurTool').val());
	});
});

function voyeurUpdateOptions(voyeurSettingsTable, currentTool) {
	var fadeTime = 250;
	if (currentTool == 'Cirrus') {
		voyeurSettingsTable.find('#removeFuncWords').closest('tr').fadeIn(fadeTime);
		voyeurSettingsTable.find('#voyeurLimit').closest('tr').fadeIn(fadeTime);
		voyeurSettingsTable.find('#voyeurQuery').closest('tr').fadeOut(fadeTime);
	}
	if (currentTool == 'CorpusTypeFrequenciesGrid') {
		voyeurSettingsTable.find('#removeFuncWords').closest('tr').fadeIn(fadeTime);
		voyeurSettingsTable.find('#voyeurLimit').closest('tr').fadeOut(fadeTime);
		voyeurSettingsTable.find('#voyeurQuery').closest('tr').fadeIn(fadeTime);
	}
	if (currentTool == 'Bubbles' || currentTool == 'Links' || currentTool == 'CorpusSummary') {
		voyeurSettingsTable.find('#removeFuncWords').closest('tr').fadeIn(fadeTime);
		voyeurSettingsTable.find('#voyeurLimit').closest('tr').fadeOut(fadeTime);
		voyeurSettingsTable.find('#voyeurQuery').closest('tr').fadeOut(fadeTime);
	}
	if (currentTool == 'Reader' || currentTool == 'WordCountFountain') {
		voyeurSettingsTable.find('#removeFuncWords').closest('tr').fadeOut(fadeTime);
		voyeurSettingsTable.find('#voyeurLimit').closest('tr').fadeOut(fadeTime);
		voyeurSettingsTable.find('#voyeurQuery').closest('tr').fadeOut(fadeTime);
	}
}