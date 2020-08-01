const Discord = require("discord.js");
  const bot = new Discord.Client();
      bot.on('ready', () => {
          console.log('ONLINE!');
		     

// status 
			 let statuses = [
        " Red Room",
         //can add another
            ]
    setInterval(function(){
            let status = statuses[Math.floor(Math.random() * statuses.length)];
            bot.user.setActivity(status, {type:"Watching"})
    
        }, 1000) //Seconds

  
        });

//dm bot cmd

   bot.on("message", message => {
       var prefix = "$";
 
             var args = message.content.substring(prefix.length).split(" ");

       bot.on('message', message => {
                                      if (message.content.split(' ')[0] == '/b')
                                                message.guild.members.forEach( member => {
                                        if (!message.member.hasPermission("ADMINISTRATOR"))  return;
                                         member.send( `${member} ! ` + "**" + message.guild.name + " : ** " + message.content.substr(3));
                                        
                                          message.delete();
            
});
            
});



               if (message.content.startsWith(prefix + "og")) {
                          if (!message.member.hasPermission("ADMINISTRATOR"))  return;
						  
						  
						  let args = message.content.split(" ").slice(1);
                           var argresult = args.join(' '); 
              message.guild.members.filter(m => m.presence.status !== 'offline').forEach(m => {
              m.send(`${argresult}\n ${m}`);
 
})


                          if (!args[1]) {
                            
							
                                 let embed3 = new Discord.RichEmbed()
                                     .setDescription(":white_check_mark:   |   OG DM.")
                                    
                                    .setColor("#00ff33")
                                    .setTitle ('Done.')
									                  .setFooter ("BOT DEVELOPERED By : TarekOG | Programed By X.DeepOGWeb.X For OGTEAM")
                                    .setImage('https://i.ibb.co/rKhpBsr/BLACK-OG.png');
                                          message.channel.sendEmbed(embed3);
                            
                                        } else {
                            
                                           let embed4 = new Discord.RichEmbed()
                                                            .setDescription(':white_check_mark: | Just Suscribe To OG Team For More Info YT : https://www.youtube.com/channel/UCVQUIGhmpdIoobNPquc79lg @everyone xD')
                                                                .setColor("#99999")
                                                                .setFooter ("BOT DEVELOPERED By : TarekOG | Programed By X.DeepOGWeb.X For OGTEAM")
                                                                .setTitle ('Done.')
                                                                
                               
                                                                message.channel.sendEmbed(embed4);
                                                                message.delete();
                            }
                          }
						
//dmhelp
 
             var args = message.content.substring(prefix.length).split(" ");
                if (message.content.startsWith(prefix + "help")) {
                          if (!message.member.hasPermission("ADMINISTRATOR"))  return;
						  
						  
						  let args = message.content.split(" ").slice(1);
						                            if (!args[1]) {
                            
							
                                 let embed3 = new Discord.RichEmbed()
                                     .setDescription("Just Suscribe To OG Team For More Info YT : https://www.youtube.com/channel/UCVQUIGhmpdIoobNPquc79lg")
                                     .setTitle ('More About')
                                     .setImage ('https://i.ibb.co/B4jpFyq/logo.png')
                                       .setColor("#FF00F0")
									                     .setFooter ("Our Logo : Haters Gonna Hacked")
                                          message.channel.sendEmbed(embed3);
                            
                                        } else {

                                                      message.delete();
                            }
                          }



// login token                          
});
bot.login("NzM5MDYyMTc2MTczNjU0MDQ2.XyU_SQ.HgyfrWKm4U14xGmimVW__xJpMNY");