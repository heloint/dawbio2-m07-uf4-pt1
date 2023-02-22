$(document).ready(function () {
    // Number of rows to display per page
    let rowsPerPage = 10;

    // Get the table and tbody elements
    let table = $("#myTable");
    let tbody = table.find("tbody");

    // Get the number of rows in the table
    let numRows = tbody.find("tr").length;

    // Calculate the number of pages
    let numPages = Math.ceil(numRows / rowsPerPage);

    // Create the pagination links
    let pagination = $('<ul class="pagination justify-content-center"></ul>');
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
        let page = $(this).text();

        // Show the rows for the selected page
        let startRow = (page - 1) * rowsPerPage;
        let endRow = startRow + rowsPerPage - 1;
        tbody
            .find("tr")
            .hide()
            .slice(startRow, endRow + 1)
            .show();

        // Update the active class for the pagination links
        pagination.find("li").removeClass("active");
        $(this).parent().addClass("active");

        // Update the previous and next buttons
        updateButtons(parseInt(page), numPages);
    });

    // Handle previous and next button click events
    $("#previousPage").click(function (event) {
        // Prevent the default link behavior
        event.preventDefault();

        // Get the current active page
        let currentPage = pagination.find("li.active").find("a").text();

        // Calculate the previous page
        let previousPage = parseInt(currentPage) - 1;
        if (previousPage < 1) {
            previousPage = 1;
        }

        // Show the rows for the previous page
        let startRow = (previousPage - 1) * rowsPerPage;
        let endRow = startRow + rowsPerPage - 1;
        tbody
            .find("tr")
            .hide()
            .slice(startRow, endRow + 1)
            .show();

        // Update the active class for the pagination links
        pagination.find("li").removeClass("active");
        pagination
            .find("li")
            .eq(previousPage - 1)
            .addClass("active");

        // Update the previous and next buttons
        updateButtons(previousPage, numPages);
    });

    $("#nextPage").click(function (event) {
        // Prevent the default link behavior
        event.preventDefault();

        // Get the current active page
        let currentPage = pagination.find("li.active").find("a").text();

        // Calculate the next page
        let nextPage = parseInt(currentPage) + 1;
        if (nextPage > numPages) {
            nextPage = numPages;
        }

        // Show the rows for the next page
        let startRow = (nextPage - 1) * rowsPerPage;
        let endRow = startRow + rowsPerPage - 1;
        tbody
            .find("tr")
            .hide()
            .slice(startRow, endRow + 1)
            .show();

        // Update the active class for the pagination links
        pagination.find("li").removeClass("active");
        pagination
            .find("li")
            .eq(nextPage - 1)
            .addClass("active");

        // Update the previous and next buttons
        updateButtons(nextPage, numPages);
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
    pagination.find("li:first-child").find("a").click();
});
