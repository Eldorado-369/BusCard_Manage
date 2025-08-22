// function resetForm() {
//     // Clear the form fields
//     const form = document.getElementById('studentForm');
//     if (form) {
//         form.reset();  // Reset form fields

//         // Clear all input fields explicitly
//         const inputs = form.getElementsByTagName('input');
//         for (let input of inputs) {
//             input.value = '';
//         }

//         // Clear select fields if any
//         const selects = form.getElementsByTagName('select');
//         for (let select of selects) {
//             select.selectedIndex = 0;
//         }
//     }

//     // Clear any error/success messages
//     const messages = document.querySelectorAll('.alert-message, .success-message');
//     messages.forEach(message => {
//         message.style.display = 'none';
//     });

//     // Reset session and refresh the page
//     fetch('reset_session.php', {
//         method: 'POST',
//         credentials: 'same-origin'
//     })
//         .then(response => response.text())
//         .then(data => {
//             // Instead of reloading, redirect to clean URL
//             window.location.href = window.location.pathname;
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             alert('Error resetting form. Please try again.');
//         });
// }

// function resetEditForm() {
//     const form = document.getElementById('editStudentForm');
//     if (form) {
//         form.reset();

//         // Clear any input fields explicitly
//         const inputs = form.getElementsByTagName('input');
//         for (let input of inputs) {
//             input.value = '';
//         }

//         // Clear any select fields
//         const selects = form.getElementsByTagName('select');
//         for (let select of selects) {
//             select.selectedIndex = 0;
//         }
//     } else {
//         console.error('editStudentForm not found');
//     }
// }


// Correctly initialize modal
// let myModal = new bootstrap.Modal(document.getElementById('myModalId'), {
//     keyboard: false
// });
// myModal.show();


// document.addEventListener('DOMContentLoaded', function () {
//     const modalElement = document.getElementById('myModalId');
//     const myModal = new bootstrap.Modal(modalElement);
//     myModal.show();
// });



// Forcefully close all modals
// $('.modal').modal('hide');
// $('.modal-backdrop').remove();
// $('body').removeClass('modal-open');



// function cleanupModal() {
//     $('.modal-backdrop').remove();
//     $('body').removeClass('modal-open');
//     $('body').css('padding-right', '');
// }



// $(document).ready(function () {
//     $(document).on('hidden.bs.modal', function () {
//         $('.modal-backdrop').remove();
//         $('body').removeClass('modal-open');
//         $('body').css('padding-right', ''); // Handles scrollbars
//     });
// });


// document.addEventListener('DOMContentLoaded', function () {
//     // DataTable Initialization (Vanilla JS equivalent)
//     function initializeDataTable(tableId) {
//         const table = document.getElementById(tableId);
//         if (!table) return;

//         // Basic search functionality
//         const searchInput = document.createElement('input');
//         searchInput.type = 'text';
//         searchInput.placeholder = 'Search...';
//         searchInput.addEventListener('keyup', function () {
//             const filter = this.value.toLowerCase();
//             const rows = table.querySelectorAll('tbody tr');

//             rows.forEach(row => {
//                 const text = row.textContent.toLowerCase();
//                 row.style.display = text.includes(filter) ? '' : 'none';
//             });
//         });

//         table.parentNode.insertBefore(searchInput, table);

//         // Responsive scroll (simulating DataTables behavior)
//         table.style.display = 'table';
//         table.style.maxHeight = '50vh';
//         table.style.overflowY = 'auto';
//     }

// Initialize tables
// initializeDataTable('studentTable');

// Modal handling
// function setupModalHandlers() {
//     const editModal = document.getElementById('editModal');
//     const editButtons = document.querySelectorAll('.edit-btn');
//     const closeModalButtons = editModal.querySelectorAll('.btn-close');
//     const editForm = document.getElementById('editStudentForm');

// Show modal on edit button click
// editButtons.forEach(editBtn => {
//     editBtn.addEventListener('click', function (e) {
//         e.preventDefault();
//         const studentId = this.getAttribute('data-id');
//         const type = this.getAttribute('data-type');
//         const row = this.closest('tr');

//         document.getElementById('editStudentId').value = studentId;
//         document.getElementById('editTableType').value = type;
//         document.getElementById('editName').value = row.cells[1].textContent;
//         document.getElementById('editBusNumber').value = row.cells[2].textContent;

//         editModal.classList.add('show');
//         editModal.style.display = 'block';
//         document.body.classList.add('modal-open');
//     });
// });

// Close modal handlers
// closeModalButtons.forEach(btn => {
//     btn.addEventListener('click', function () {
//         editModal.classList.remove('show');
//         editModal.style.display = 'none';
//         document.body.classList.remove('modal-open');
//     });
// });

// Form submission handler
//     if (editForm) {
//         editForm.addEventListener('submit', function (e) {
//             e.preventDefault();

//             const formData = new FormData(this);

//             fetch('update_student.php', {
//                 method: 'POST',
//                 body: formData
//             })
//                 .then(response => {
//                     if (response.ok) {
//                         alert('Student updated successfully!');
//                         window.location.reload();
//                     } else {
//                         throw new Error('Update failed');
//                     }
//                 })
//                 .catch(error => {
//                     console.error('Error:', error);
//                     alert('Error updating student.');
//                 });
//         });
//     }
// }

// Alert handling
//     function setupAlertHandlers() {
//         const closeAlertButtons = document.querySelectorAll('.close-btn');

//         closeAlertButtons.forEach(button => {
//             button.addEventListener('click', function () {
//                 const messages = document.querySelectorAll('.success-message, .alert-message');
//                 messages.forEach(message => {
//                     message.style.display = 'none';
//                 });
//             });
//         });
//     }

//     // Initialize modal and alert handlers
//     setupModalHandlers();
//     setupAlertHandlers();
// });

// Additional utility functions can be added here
// function resetForm() {
//     // Reset form functionality
//     document.getElementById('applicationForm').reset();
// }



// already exist below





// toggle sidebar
document.addEventListener('DOMContentLoaded', () => {

    //animating 2nd division
    const secondDivision = document.querySelector('.rightPaneSecondDivision');
    const allBars = document.querySelectorAll(
        '.rightPaneSecondDivisionBottomTopRightOneBottomBar, ' +
        '.rightPaneSecondDivisionBottomTopRightTwoBottomBar, ' +
        '.rightPaneSecondDivisionBottomTopRightThreeBottomBar, ' +
        '.rightPaneSecondDivisionBottomTopRightFourBottomBar, ' +
        '.rightPaneSecondDivisionBottomTopRightFiveBottomBar'
    );

    function handleScroll() {
        const secondDivisionRect = secondDivision.getBoundingClientRect();
        if (secondDivisionRect.top < window.innerHeight && secondDivisionRect.bottom >= 0) {
            secondDivision.classList.add('animate');
            allBars.forEach(bar => {
                bar.classList.add('animate');
            });
            window.removeEventListener('scroll', handleScroll);
        }
    }
    window.addEventListener('scroll', handleScroll);
    handleScroll();
    //animating 2nd division (end)

    //toggling
    const toggleButton = document.getElementById('toggleButton');
    const sideNavbar = document.getElementById('sideNavigationBar');
    const rightPane = document.querySelector('.rightPane');
    const dashboardTextAndIcons = document.querySelector('.dashboardTextAndIcons');
    const topIcons = document.querySelector('.topIcons');
    const rightPaneFirstDivision = document.querySelector('.rightPaneFirstDivision');
    const rightPaneFirstDivisionTop = document.querySelector('.rightPaneFirstDivisionTop');
    const rightPaneFirstDivisionTopLeft = document.querySelector('.rightPaneFirstDivisionTopLeft');
    const rightPaneFirstDivisionBottom = document.querySelector('.rightPaneFirstDivisionBottom');
    const allTodayBars = document.querySelectorAll(
        '.rightPaneFirstDivisionBottomBottomOne, ' +
        '.rightPaneFirstDivisionBottomBottomTwo, ' +
        '.rightPaneFirstDivisionBottomBottomThree, ' +
        '.rightPaneFirstDivisionBottomBottomFour, ' +
        '.rightPaneFirstDivisionBottomBottomFive, ' +
        '.rightPaneFirstDivisionBottomBottomSix, ' +
        '.rightPaneFirstDivisionBottomBottomSeven, ' +
        '.rightPaneFirstDivisionBottomBottomEight'
    );
    const rightPaneFirstDivisionBottomTopRightTop = document.querySelector('.rightPaneFirstDivisionBottomTopRightTop');
    const rightPaneSecondDivision = document.querySelector('.rightPaneSecondDivision');

    toggleButton.addEventListener('click', () => {
        sideNavbar.classList.toggle('minimised');

        // Toggle icon based on state
        if (sideNavbar.classList.contains('minimised')) {
            toggleButton.classList.remove('fa-minimize');
            toggleButton.classList.add('fa-maximize');
            rightPane.style.width = '83em';
            rightPane.style.marginLeft = '10em';
            dashboardTextAndIcons.style.width = '80em';
            topIcons.style.marginLeft = '15.5em';
            rightPaneFirstDivision.style.width = '81.5em';
            rightPaneFirstDivisionTop.style.width = '80.5em';
            rightPaneFirstDivisionTopLeft.style.width = '35.5em';
            rightPaneFirstDivisionBottom.style.width = '78.5em';
            allTodayBars.forEach(todayBar => {
                todayBar.style.marginLeft = '4.2em';
            });
            rightPaneFirstDivisionBottomTopRightTop.style.marginLeft = '74em';
            rightPaneSecondDivision.style.width = '78.5em';
        } else {
            toggleButton.classList.remove('fa-maximize');
            toggleButton.classList.add('fa-minimize');
            rightPane.style.width = '69.5em';
            rightPane.style.marginLeft = '23.5em';
            dashboardTextAndIcons.style.width = '66em';
            topIcons.style.marginLeft = '1.5em';
            rightPaneFirstDivision.style.width = '68em';
            rightPaneFirstDivisionTop.style.width = '67em';
            rightPaneFirstDivisionTopLeft.style.width = '22em';
            rightPaneFirstDivisionBottom.style.width = '65em';
            allTodayBars.forEach(todayBar => {
                todayBar.style.marginLeft = '2.7em';
            });
            rightPaneFirstDivisionBottomTopRightTop.style.marginLeft = '47em';
            rightPaneSecondDivision.style.width = '65em';
        }
    });
    //toggling (end)
});


function filterTable() {
    const editSearchBar = document.querySelector("#editBusCardDiv #searchBar");
    const viewSearchBar = document.querySelector("#viewBusCardDiv #searchBar");
    const paymentSearchBar = document.querySelector("#paymentHistoryDiv #searchBar");

    function filterSpecificTable(searchBar, tableId) {
        if (!searchBar) return;

        const searchText = searchBar.value.toLowerCase();
        const table = document.getElementById(tableId);
        if (!table) return;

        const rows = table
            .getElementsByTagName("tbody")[0]
            .getElementsByTagName("tr");

        for (let row of rows) {
            let cells = row.getElementsByTagName("td");
            let matchFound = false;

            for (let cell of cells) {
                let text = cell.textContent || cell.innerText;
                if (text.toLowerCase().includes(searchText)) {
                    matchFound = true;
                    break;
                }
            }

            // Show/hide row based on whether it matches the search
            row.style.display = matchFound ? "" : "none";
        }
    }

    // Add event listeners for all search bars
    if (editSearchBar) {
        editSearchBar.addEventListener("input", () =>
            filterSpecificTable(editSearchBar, "editBusCardDiv")
        );
    }

    if (viewSearchBar) {
        viewSearchBar.addEventListener("input", () =>
            filterSpecificTable(viewSearchBar, "viewBusCardDiv")
        );
    }

    if (paymentSearchBar) {
        paymentSearchBar.addEventListener("input", () =>
            filterSpecificTable(paymentSearchBar, "paymentHistoryDiv")
        );
    }
}

document.addEventListener("DOMContentLoaded", filterTable);