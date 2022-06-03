
document.getElementById("theme_black").onclick = function() {
  document.querySelector(':root').style.setProperty('--white', "#25292e");
  document.querySelector(':root').style.setProperty('--black', "white");
  document.querySelector(':root').style.setProperty('--dark-white', "#202428");
  document.querySelector(':root').style.setProperty('--border-white', "#E5EBF0");
  
}
document.getElementById("theme_white").onclick = function() {
  document.querySelector(':root').style.setProperty('--white', "white");
  document.querySelector(':root').style.setProperty('--black', "#25292e");
  document.querySelector(':root').style.setProperty('--dark-white', "#f5f5f5");
  document.querySelector(':root').style.setProperty('--border-white', "#dddddd");


}