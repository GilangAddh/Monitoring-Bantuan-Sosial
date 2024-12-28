import "./bootstrap";
import Swal from "sweetalert2";
window.SwalGlobal = Swal.mixin({
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
});
