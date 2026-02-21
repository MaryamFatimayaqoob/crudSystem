document.addEventListener("DOMContentLoaded", function(){

    loadStudents();

    document.getElementById("searchBox")?.addEventListener("keyup", function(){
        loadStudents(this.value);
    });

});

async function loadStudents(search=""){

    let res = await fetch("../api/student_api.php?search="+search);
    let data = await res.json();

    let tbody = document.getElementById("studentTable");

    tbody.innerHTML = "";

    data.forEach(s=>{
        tbody.innerHTML += `
        <tr>
        <td>${s.id}</td>
        <td>${s.roll_no}</td>
        <td>${s.name}</td>
        <td>${s.email}</td>
        <td>${s.department}</td>
        <td>${s.semester}</td>
        <td>${s.cgpa}</td>
        <td>${s.status}</td>
        <td>
        <button onclick="deleteStudent(${s.id})" class="btn btn-danger btn-sm">
        Delete
        </button>
        </td>
        </tr>
        `;
    });

}

async function deleteStudent(id){

    if(!confirm("Delete student?")) return;

    let fd = new FormData();
    fd.append("delete", id);

    await fetch("../api/student_api.php",{method:"POST", body:fd});

    loadStudents();
}