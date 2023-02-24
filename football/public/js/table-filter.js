
function filterTableRows(tableId, substring) {
  var table = $(tableId);
  var rows = table.find('tr');

  rows.each(function(index) {
    // Skip the first row (assumed to be the header row)
    if (index === 0) {
      return true; // Equivalent to "continue" in a normal for loop
    }
    
    var row = $(this);
    var rowText = row.text().toLowerCase();
    
    if (rowText.indexOf(substring.toLowerCase()) === -1) {
      row.hide();
    } else {
      row.show();
    }
  });
}

  $(document).ready(function() {
    // Attach a click handler to the search button
    $('#search-button').click(function() {
      var searchString = $('#search-input').val();
      filterTableRows('.filterable-table', searchString);
    });

    // Attach a keyup handler to the search input to filter as the user types
    $('#search-input').on('keyup', function() {
      var searchString = $(this).val();
      filterTableRows('.filterable-table', searchString);
    });
  });
