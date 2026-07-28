document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[onsubmit]').forEach(function (form) {
        var original = form.onsubmit;
        form.onsubmit = null;
        form.addEventListener('submit', function (e) {
            if (typeof original === 'function') {
                return original.call(form, e);
            }
        });
    });

    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            var filter = this.value.toLowerCase();
            var rows = document.querySelectorAll('tbody tr');
            rows.forEach(function (row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
});
