fetch("http://127.0.0.1:8000/api/user")
.then(users =>{
      return users.json();
})
.then(users =>{
      users.forEach(users => {
      const li = document.createElement("li");
      li.textContent = users.lastname;
      const li2 = document.createElement("li");
      li2.textContent = users.firstname;
      const li3 = document.createElement("li");
      li3.textContent = users.mail;
      document.getElementById("liste").appendChild(li);
      document.getElementById("liste").appendChild(li2);
      document.getElementById("liste").appendChild(li3);
      li3.style.marginBottom = "20px"; 
      });
})