$(document).ready(function () {
    // Alert auto fade & slide
    $(".alert")
        .fadeTo(5000, 500)
        .slideUp(500, function () {
            $(".alert").slideUp(500);
        });

    // Phone number validation (for both name="phone" and .input_phone)
    $(document).on("change", 'input[name="phone"], .input_phone', function () {
        let inputValue = $(this).val();
        let pattern = /^(6|7|8|9)\d{9}$/;

        if (!pattern.test(inputValue)) {
            alert(
                "Please enter a valid Indian phone number starting with 6, 7, 8, or 9 and having exactly 10 digits."
            );
            $(this).val(""); // clear invalid input
        }
    });
});

// Delete confirmation + form submit
function deleteData(url) {
    if (confirm("Are you sure you want to delete this data?")) {
        let form = document.createElement("form");
        form.method = "POST";
        form.action = url;

        let csrf = document.createElement("input");
        csrf.type = "hidden";
        csrf.name = "_token";
        csrf.value = "{{ csrf_token() }}";

        let method = document.createElement("input");
        method.type = "hidden";
        method.name = "_method";
        method.value = "DELETE";

        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}

// Image preview for single image input
$(document).on("change", ".image-preview-input", function () {
    let input = this;
    let previewId = $(this).data("preview");

    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            $("#" + previewId)
                .attr("src", e.target.result)
                .show();
        };
        reader.readAsDataURL(input.files[0]);
    }
});

// Image preview for multiple (by class)
$(document).on("change", ".image-preview-input-2", function () {
    let input = this;
    let previewClass = $(this).data("preview");

    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            $("." + previewClass)
                .attr("src", e.target.result)
                .show();
        };
        reader.readAsDataURL(input.files[0]);
    }
});


// Function to set the minimum date to today
function setMinDate(selector) {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, "0");
    const dd = String(today.getDate()).padStart(2, "0");
    const formattedToday = `${yyyy}-${mm}-${dd}`;
    // $(selector).attr("min", formattedToday).val(formattedToday); // if set min date
    $(selector).attr("min", formattedToday);
}

// Function to set the minimum time to now
function setMinTime(selector) {
    const now = new Date();
    const hh = String(now.getHours()).padStart(2, "0");
    const mm = String(now.getMinutes()).padStart(2, "0");
    const formattedNow = `${hh}:${mm}`;
    // $(selector).attr('min', formattedNow).val(
    // formattedNow);
}
