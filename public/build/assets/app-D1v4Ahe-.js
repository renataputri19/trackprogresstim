import{C as a,i as o,a as s,b as c,c as y}from"./index-CBQq8RrD.js";document.addEventListener("DOMContentLoaded",function(){let i=document.getElementById("calendar");if(i){let r=new a(i,{plugins:[o,s,c,y],initialView:"dayGridMonth",events:"/admin/calendar/events",headerToolbar:{left:"prev,next today",center:"title",right:"dayGridMonth,timeGridWeek,listWeek"},eventContent:function(l){let e=document.createElement("div");e.style.display="flex",e.style.alignItems="center",e.style.width="100%",e.style.height="100%",e.style.backgroundColor=l.event.backgroundColor,e.style.position="relative";let n=document.createElement("span");n.innerText=`${l.event.title} - ${l.event.extendedProps.progress}%`,n.style.flex="1",n.style.zIndex="2",n.style.color="black",n.style.padding="0 10px",n.style.textAlign="left";let t=document.createElement("div");t.style.width="100%",t.style.height="100%",t.style.backgroundColor="#e9ecef",t.style.position="absolute",t.style.top="0",t.style.left="0",t.style.zIndex="1";let d=document.createElement("div");return d.style.width=l.event.extendedProps.progress+"%",d.style.height="100%",d.style.backgroundColor="#28a745",d.style.borderRadius="3px",t.appendChild(d),e.appendChild(t),e.appendChild(n),{domNodes:[e]}}});r.render(),document.getElementById("tim-filter").addEventListener("change",function(){let l=this.value;r.getEventSources().forEach(function(e){e.remove()}),r.addEventSource({url:"/admin/calendar/events",extraParams:{tim:l}}),r.refetchEvents()})}});