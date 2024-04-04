let mode;
if (idMode == 1) {
    body.classList.toggle("dark");
    modeText.innerText = "День";
} else {
    modeText.innerText = "Ночь";
    body.classList.remove("dark");
}
modeSwitch.addEventListener("click", (event) => {
    body.classList.toggle("dark");

    if (body.classList.contains("dark")) {
        modeText.innerText = "День";
        mode = 1;
    } else {
        modeText.innerText = "Ночь";
        mode = 0;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: modeUrl,
        type: "POST",
        data: {
            id: idUserMode,
            mode: mode,
        },
        success: function (response) {
            //  console.log(response);
        },
    });
    //  console.log(mode);
});

let sidebarMode;
if (sidebarIdMode == 0) {
    sidebar.classList.add("close-sidebar");
} else {
    sidebar.classList.remove("close-sidebar");
}

toggle.addEventListener("click", (event) => {
    sidebar.classList.toggle("close-sidebar");

    if (sidebar.classList.contains("close-sidebar")) {
        sidebarMode = 0;
    } else {
        sidebarMode = 1;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: sidebarUrl,
        type: "POST",
        data: {
            id: idUserMode,
            sidebarMode: sidebarMode,
        },
        success: function (response) {
            // console.log(response);
        },
    });
    // console.log(sidebarMode);
});
