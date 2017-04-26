{"version":3,"file":"core_webrtc.min.js","sources":["core_webrtc.js"],"names":["window","BX","webrtc","this","debug","audioMuted","videoMuted","enabled","detectedBrowser","attachMediaStream","pcConfig","oneway","lastUserMediaParams","pcConstraints","sdpConstraints","mandatory","OfferToReceiveAudio","OfferToReceiveVideo","defaultMicrophone","defaultCamera","enableMicAutoParameters","configVideo","maxWidth","maxHeight","minWidth","minHeight","configVideoGroup","configVideoMobile","configVideoAfterError","callStreamSelf","callStreamMain","callStreamUsers","pc","pcStart","connected","iceCandidates","initiator","callUserId","callChatId","callToGroup","callGroupUsers","callInit","callInitUserId","callActive","callVideo","callRequestUserMedia","needPeerConnection","createAnswerTimeout","initPeerConnectionTimeout","iceCandidateTimeout","pcConnectTimeout","adapter","setTurnServer","inheritWebrtc","child","prototype","constructor","parent","navigator","mozGetUserMedia","userAgent","substr","indexOf","RTCPeerConnection","mozRTCPeerConnection","RTCSessionDescription","mozRTCSessionDescription","RTCIceCandidate","mozRTCIceCandidate","getUserMedia","bind","element","stream","mozSrcObject","play","webkitGetUserMedia","appVersion","webkitRTCPeerConnection","constraintsToChrome_","c","optional","cc","Object","keys","forEach","key","r","ideal","exact","undefined","min","max","oldname_","prefix","name","charAt","toUpperCase","slice","oc","push","mix","advanced","concat","getUserMedia_","constraints","onSuccess","onError","JSON","parse","stringify","audio","video","src","URL","createObjectURL","webkitMediaStream","getVideoTracks","videoTracks","getAudioTracks","audioTracks","MediaStreamTrack","getSources","e","func","getLocalStreams","localStreams","getRemoteStreams","remoteStreams","mediaDevices","Promise","resolve","reject","enumerateDevices","kinds","devices","map","device","label","kind","deviceId","id","groupId","MediaStream","ready","params","turnServer","turnServerFirefox","turnServerLogin","turnServerPassword","iceServers","url","credential","username","DtlsSrtpKeyAgreement","toggleAudio","changeVariable","length","i","toggleVideo","signalingReady","PULL","getPullServerStatus","log","text","desktop","arguments","message","console","startGetUserMedia","onUserMediaSuccess","echoCancellation","googEchoCancellation","googEchoCancellation2","googDAEchoCancellation","googAutoGainControl","googAutoGainControl2","mozAutoGainControl","googNoiseSuppression","googNoiseSuppression2","googHighpassFilter","googTypingNoiseDetection","googAudioMirroring","delegate","onUserMediaError","stop","userId","clearTimeout","initPeerConnection","error","createPeerConnection","setTimeout","sendOfferToPeer","setLocalAndSend","desc","setLocalDescription","a","onRemoteStreamAdded","event","setMainVideo","onRemoteStreamRemoved","onIceCandidate","candidates","onIceConnectionStateChange","onSignalingStateChange","peerConnectionError","peerConnectionReconnect","iceConnectionState","signalingState","close","deleteEvents","hasOwnProperty","stopMediaStream","mergeConstraints","cons1","cons2","merged","MozDontOfferDataChannel","prop","createOffer","onicecandidate","onIceCandidateEvent","onaddstream","onRemoteStreamAddedEvent","onremovestream","onRemoteStreamRemovedEvent","oniceconnectionstatechange","onIceConnectionStateChangeEvent","onsignalingstatechange","onSignalingStateChangeEvent","addStream","signalingPeerData","peerData","signal","type","createAnswer","setRemoteDescription","candidate","sdpMLineIndex","addIceCandidate","sdpMid","setDefaultCamera","setDefaultMicrophone","mediaStream","getTracks","track"],"mappings":"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"}