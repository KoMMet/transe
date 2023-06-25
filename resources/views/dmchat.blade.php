<!DOCTYPE html>

<head>
    <mata charset=UTF-8>
    <title>DmChat</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        .class-my-message {
            margin:0.6rem;
            background: lightgray;
        }
        .class-my-message > .class-src-message {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .class-my-message > .class-trns-message {
            color : dimgray; 
            font-size: 1rem;
        }

        .class-friend-message {
            margin:0.6rem;
            background: mistyrose;
        }

        .class-friend-message > .class-src-message {
            color : dimgray; 
            font-size: 1rem;
        }

        .class-friend-message > .class-trns-message {
            font-weight: bold;
            font-size: 1.2rem;
        }

        #id-input-message, #id-send-button {
            font-size:1.2rem;
        }
    </style>
</head>

<body>
    <h1>DM - {{$friendId}}</h1>

    <form>
        <textarea id="id-input-message"></textarea>
        <input id="id-send-button" type="button" value="send">
    </form>

    <div id="id-message"></div>

    <script>
        let button = document.getElementById("id-send-button"); 
        let inputMessage = document.getElementById("id-input-message");
        let myUserId = "<?php echo Auth::user()->id ?>";
        let myUserName ="<?php echo Auth::user()->name ?>";
        let myFriendId = {{$friendId}};

        //初期表示
        window.onload = () =>
        {
            readMessage(myFriendId, myUserId);
        }

        inputMessage.addEventListener("keypress", (e)=>{
            //Enterキー入力
            if(e.keyCode === 13){
                 updateMessageParts();   
            }
        });

        //sendボタン押下 
        button.addEventListener("click", () =>{
            updateMessageParts();
        });

        const updateMessageParts = () => {
             let myMessage = document.getElementById("id-input-message").value;

             if(myMessage === "")
             {
                 removeMessage();
                 readMessage(myFriendId, myUserId);
                 return;
             }

             let mes = document.createElement("div");
             mes.className = "class-my-message";
             mes.textContent =myUserName + ":" + myMessage;
             document.getElementById("id-message").appendChild(mes);

             axios.post("/api/dmchat/add", { "myUserId" : myUserId, "myMessage" : myMessage, "friendId" : {{$friendId}} })
                 .then(function (res) {
                     removeMessage();
                     readMessage(myFriendId, myUserId);
                 })
                 .catch(function (e) {
                     console.log(e);
                    alert(e);
                 });

             document.getElementById("id-input-message").value = "";
        }

        const removeMessage = () =>
        {
            let parent = document.getElementById("id-message");
            while(parent.lastChild)
            {
                parent.removeChild(parent.lastChild);
            }
        }

        const readMessage = (friendId, myUserId) =>
        {
            console.log(friendId);
            axios.post("/api/dmchat/read", { "myUserId" : myUserId, "friendId" : friendId})
                .then(function (res) {
                    console.log(res);
                    const obj = JSON.parse(res.data);

                    for(let i = 0; i < obj.length; i++)
                    {
                        let item = obj[i];
                        let mesEle = document.createElement("div");
                        let name = "";

                        if(Number(myUserId) === item["sender_id"])
                        {
                            mesEle.className = "class-my-message";
                            name = "Me";
                        }
                        else
                        {
                            mesEle.className = "class-friend-message";
                            name = item["sender_name"];
                        }

                        let mesSrcEle = document.createElement("div");
                        mesSrcEle.className = "class-src-message";
                        mesSrcEle.textContent = item["src_message"];
                        mesEle.appendChild(mesSrcEle);

                        let mesTrnsEle = document.createElement("div");
                        mesTrnsEle.className = "class-trns-message";
                        mesTrnsEle.textContent = name + " : " + item["trns_message"];
                        mesEle.appendChild(mesTrnsEle);

                       // mesEle.textContent = item["sender_id"] + ":" + item["src_message"];
                        document.getElementById("id-message").appendChild(mesEle);
                    }
                })
                .catch(function (e) {
                    console.log(e);
                    alert(e);
                });
            console.log(friendId);
            return;
        }
    </script>

</body>
