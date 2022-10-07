/*  document.onkeypress = function (e) {
      e = e || window.event;

      if (e.keyCode === 13) {
          document.documentElement.classList.toggle('dark-mode');
          document.querySelectorAll('.inverted').forEach((result) => {
              result.classList.toggle('invert');
          })
      }
  }

 */


/*function myFunctionToggle() {


    document.documentElement.classList.toggle('dark-mode');
    document.querySelectorAll('.inverted').forEach((result) => {
        result.classList.toggle('invert');
    });


}*/

/*function myFunctionToggle() {
    const checkbox = document.getElementById('checkbox');

    checkbox.addEventListener('change', () => {

        document.documentElement.classList.toggle('dark-mode');
        document.querySelectorAll('.inverted').forEach((result) => {
            result.classList.toggle('invert');
        });

    })

}*/

/*function myFunctionToggle() {
    const checkbox = document.getElementById('checkbox');
    checkbox.addEventListener('change', () => {
        function myFunction() {
            document.documentElement.classList.toggle('dark-mode');
            document.querySelectorAll('.inverted').forEach((result) => {
                result.classList.toggle('invert');
            });
        }
    })
}*/

/*function myFunctionToggle() {
    let checkbox = document.getElementById('checkbox');
    checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
            document.documentElement.classList.toggle('dark-mode');
            document.querySelectorAll('.inverted').forEach((result) => {
                result.classList.toggle('invert');
            });
        } else {
            document.documentElement.classList.toggle('dark-mode');
            document.querySelectorAll('.inverted').forEach((result) => {
                result.classList.toggle('invert');
            });
        }

    })
}*/


let darkMode = localStorage.getItem("dark-mode");

if (darkMode == "true") {
    addDarkMode();
}

document.querySelector(".switch").addEventListener("click", function () {
    darkMode = localStorage.getItem("dark-mode");
    if (darkMode == "true") {
        removeDarkMode();
    } else {
        addDarkMode();
    }
});

function addDarkMode() {
    darkMode = localStorage.setItem("dark-mode", "true");
    document.getElementsByTagName("body")[0].classList.add("dark-mode");

}

function removeDarkMode() {
    darkMode = localStorage.setItem("dark-mode", "false");
    document.getElementsByTagName("body")[0].classList.remove("dark-mode");

}
