//Requête Ajax
document.body.addEventListener('load',loadList());

function loadList(){

    const req=new XMLHttpRequest();

    //This url depends on what server to execute the data/E_glizy.php file

    var url="http://localhost/E_glizy/index.php?route=listeEglise";
    req.open("GET", url,true);
    req.send(null);

    req.onreadystatechange = function(){
    if(req.status==200 && req.readyState==4){
        data=req.response
        data=JSON.parse(data)

        data.forEach(element => {
            let li =document.createElement("li");
            let anchor=document.createElement("a");
            anchor.innerHTML = element.design
            anchor.setAttribute("href","http://localhost/E_glizy/index.php?route=details&id="+element.idEglise);
            anchor.setAttribute("style","text-decoration:none;color:black")
            li.appendChild(anchor);
            document.getElementById("eglise_list").appendChild(li);
        });

        var eglises=document.querySelectorAll('.list_container ul li');
        eglises[0].className="active"
        eglises.forEach(li => {
            li.addEventListener('mouseover',function(event) {
                eglises.forEach(element => {
                    element.classList.remove("active");
                });
                li.classList.add('active');
            })
        });

        //console.log(data)
    }
};

}


document.querySelector('.input-box label').addEventListener('click',function(e) {
    document.querySelector('.input-box input').focus();
})