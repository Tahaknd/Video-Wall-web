let showMenu=false;
function toggleMenu(){
    const menu=document.querySelector(".menu")
    if(showMenu==true){
        showMenu=false;
        menu.classList.remove("active");
    }
    else{
        showMenu=true;
        menu.classList.add("active");
    }
}