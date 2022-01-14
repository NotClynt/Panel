if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}
function notify(text,mode,pos='top-right'){Snackbar.show({pos:pos,text:text,backgroundColor:mode===1?'var(--primary)':mode==2?'#d1a366':'#D16666',textColor:'#FFFFFF',duration:3000,actionText:null});}
function is_mobile(){return navigator.userAgent.match(/Android/i)||navigator.userAgent.match(/webOS/i)||navigator.userAgent.match(/iPhone/i)||navigator.userAgent.match(/iPad/i)||navigator.userAgent.match(/iPod/i)||navigator.userAgent.match(/BlackBerry/i)}
var m_strUpperCase="ABCDEFGHIJKLMNOPQRSTUVWXYZ";var m_strLowerCase="abcdefghijklmnopqrstuvwxyz";var m_strNumber="0123456789";var m_strCharacters="!@#$%^&*?_~"
function countContain(strPassword,strCheck){var nCount=0;for(i=0;i<strPassword.length;i++){if(strCheck.indexOf(strPassword.charAt(i))>-1){nCount++;}}
return nCount;}
function checkPassword(strPassword){var nScore=0;if(strPassword.length<5){nScore+=3;}
else if(strPassword.length>4&&strPassword.length<8){nScore+=16;}
else if(strPassword.length>7){nScore+=23;}
var nUpperCount=countContain(strPassword,m_strUpperCase);var nLowerCount=countContain(strPassword,m_strLowerCase);var nLowerUpperCount=nUpperCount+nLowerCount;if(nUpperCount==0&&nLowerCount!=0){nScore+=(10+(nLowerCount/3)+(nUpperCount/3));}
else if(nUpperCount!=0&&nLowerCount!=0){nScore+=(20+(nLowerCount/3)+(nUpperCount/3));}
var nNumberCount=countContain(strPassword,m_strNumber);if(nNumberCount==1){nScore+=10;}
if(nNumberCount>=2){nScore+=(12+(nNumberCount/3));}
var nCharacterCount=countContain(strPassword,m_strCharacters);if(nCharacterCount==1){nScore+=10;}
if(nCharacterCount>=2){nScore+=(12+(nCharacterCount/3));}
if(nNumberCount!=0&&nLowerUpperCount!=0){nScore+=4;}
if(nNumberCount!=0&&nLowerUpperCount!=0&&nCharacterCount!=0){nScore+=6;}
if(nNumberCount!=0&&nUpperCount!=0&&nLowerCount!=0&&nCharacterCount!=0){nScore+=8;}
if(strPassword.length<7){nScore-=10;}
return nScore;}
var scroll=$(window).scrollTop();if(scroll>70){$("#navbar").addClass("active");}else{$("#navbar").removeClass("active");}
$(window).scroll(function(){var scroll=$(window).scrollTop();if(scroll>70){$("#navbar").addClass("active");}else{$("#navbar").removeClass("active");}});