$(document).ready(function () {
    // Number of rows to display per page
    const rowsPerPage = 10;
    let currentPageNumber = 1;

    // Get the table and tbody elements
    const table = $(".paginated-table");
    const tbody = table.find("tbody");

    // Get the number of rows in the table
    const numRows = tbody.find("tr").length;

    // Calculate the number of pages
    const numPages = Math.ceil(numRows / rowsPerPage);

    // Create the pagination links
    const pagination = $(".pagination");

    // Add enumerated buttons.
    for (let i = 1; i <= numPages; i++) {
        let pageLink = $('<a class="page-link" href="#"></a>').text(i);
        let pageItem = $('<li class="page-item"></li>').append(pageLink);
        pagination.append(pageItem);
    }

    // Add the pagination links to the container element
    let container = $("#paginationLinks");
    container.append(pagination);

    // Add the next button to the pagination links
    let nextPage = $('<li class="page-item" id="nextPage"></li>');
    let nextLink = $('<a class="page-link" href="#" aria-label="Next"></a>');
    nextLink.append($('<span aria-hidden="true">&raquo;</span>'));
    nextLink.append($('<span class="sr-only">Next</span>'));
    nextPage.append(nextLink);
    pagination.append(nextPage);

    // Hide all rows except the first page
    tbody.find("tr:gt(" + (rowsPerPage - 1) + ")").hide();

    // Handle pagination link click events
    pagination.find("a").click(function (event) {
        event.preventDefault();

        const clickedAriaLabel =
            event.currentTarget.attributes["aria-label"]?.value;

        if (clickedAriaLabel !== undefined) {
            if ("next" === clickedAriaLabel.toLowerCase()) {
                currentPageNumber += 1;
            } else if ("previous" === clickedAriaLabel.toLowerCase()) {
                currentPageNumber -= 1;
            }
        } else {
            currentPageNumber = parseInt($(this).text());
        }

        // Show the rows for the selected currentPageNumber
        const startRow = (currentPageNumber - 1) * rowsPerPage;
        const endRow = startRow + rowsPerPage - 1;
        tbody
            .find("tr")
            .hide()
            .slice(startRow, endRow + 1)
            .show();

        // Update the active class for the pagination links
        pagination.find("li").removeClass("active");
        $(`a:contains('${currentPageNumber}')`)
            .filter(function () {
                return $(this).text().trim() === `${currentPageNumber}`;
            })
            .parent()
            .addClass("active");

        // Update the previous and next buttons
        updateButtons(parseInt(currentPageNumber), numPages);
    });

    // Function to update the previous and next buttons
    function updateButtons(currentPage, numPages) {
        // Update the previous button
        if (currentPage == 1) {
            $("#previousPage").addClass("disabled");
        } else {
            $("#previousPage").removeClass("disabled");
        }

        // Update the next button
        if (currentPage == numPages) {
            $("#nextPage").addClass("disabled");
        } else {
            $("#nextPage").removeClass("disabled");
        }
    }

    // Show the first page
    $(pagination.find("li")[1]).find("a").click();
});
