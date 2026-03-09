let callDuration = 0;
let callTimer;
let isMuted = false;
let isSpeakerOn = false;
let isOnHold = false;
let currentCall = null;

// Debug function
function debug(message) {
    // Only log to browser console, remove UI debug console
    console.log(message);
}

const callState = {
    isRinging: false,
    isActive: false,
    isMuted: false,
    isSpeaker: false,
    popupWindow: null,
    timerInterval: null
};

// DOM Elements
let incomingCallNotification;
let voiceCallPopup;
let callerName;
let callerNumber;
let callerAvatar;
let activeCallerName;
let activeCallerNumber;
let activeCallerAvatar;
let callTimerElement;
let muteButton;
let speakerButton;
let holdButton;
let endButton;
let userEmail;
let userAddress;

// Initialize elements
function initializeElements() {
    try {
        debug('Initializing DOM elements...');
        incomingCallNotification = document.getElementById('incomingCallNotification');
        voiceCallPopup = document.getElementById('voiceCallPopup');
        callerName = document.getElementById('callerName');
        callerNumber = document.getElementById('callerNumber');
        callerAvatar = document.getElementById('callerAvatar');
        activeCallerName = document.getElementById('activeCallerName');
        activeCallerNumber = document.getElementById('activeCallerNumber');
        activeCallerAvatar = document.getElementById('activeCallerAvatar');
        callTimerElement = document.getElementById('callTimer');
        muteButton = document.getElementById('muteButton');
        speakerButton = document.getElementById('speakerButton');
        holdButton = document.getElementById('holdButton');
        endButton = document.getElementById('endButton');
        userEmail = document.getElementById('userEmail');
        userAddress = document.getElementById('userAddress');

        // Make cards draggable
        if (incomingCallNotification) {
            makeDraggable(incomingCallNotification);
            debug('Incoming call notification initialized');
        }
        if (voiceCallPopup) {
            makeDraggable(voiceCallPopup);
            debug('Voice call popup initialized');
        }

        debug('All elements initialized successfully');
        return true;
    } catch (error) {
        debug('Error initializing elements: ' + error.message);
        return false;
    }
}

// Function to format phone number
function formatPhoneNumber(phone) {
    // Remove all non-digit characters
    phone = phone.replace(/\D/g, '');

    // If starts with 0, replace with 62
    if (phone.startsWith('0')) {
        phone = '62' + phone.substring(1);
    }

    return phone;
}

// Function to update user data in the UI
function updateUserData(userData) {
    if (userData) {
        document.getElementById('activeCallerName').textContent = userData.name || 'Unknown Caller';
        document.getElementById('activeCallerAvatar').src = userData.avatar || '/assets/images/users/avatar-1.jpg';
        document.getElementById('userEmail').textContent = userData.email || 'N/A';
        document.getElementById('userAddress').textContent = userData.address || 'N/A';
    } else {
        document.getElementById('activeCallerName').textContent = 'Unknown Caller';
        document.getElementById('activeCallerAvatar').src = '/assets/images/users/avatar-1.jpg';
        document.getElementById('userEmail').textContent = 'N/A';
        document.getElementById('userAddress').textContent = 'N/A';
    }
}

function startCallTimer() {
    callDuration = 0;
    callTimer = setInterval(() => {
        callDuration++;
        const minutes = Math.floor(callDuration / 60);
        const seconds = callDuration % 60;
        document.querySelector('.call-timer').textContent =
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }, 1000);
}

function stopCallTimer() {
    if (callTimer) {
        clearInterval(callTimer);
        callTimer = null;
    }
}

function toggleMute() {
    if (!initializeElements()) {
        debug('Failed to initialize elements');
        return;
    }

    callState.isMuted = !callState.isMuted;
    if (muteButton) {
        if (callState.isMuted) {
            muteButton.classList.remove('bg-gray-200');
            muteButton.classList.add('bg-red-500', 'text-white');
            muteButton.innerHTML = '<i class="fas fa-microphone-slash"></i>';
        } else {
            muteButton.classList.remove('bg-red-500', 'text-white');
            muteButton.classList.add('bg-gray-200');
            muteButton.innerHTML = '<i class="fas fa-microphone"></i>';
        }
    }
}

function toggleSpeaker() {
    if (!initializeElements()) {
        debug('Failed to initialize elements');
        return;
    }

    callState.isSpeaker = !callState.isSpeaker;
    if (speakerButton) {
        if (callState.isSpeaker) {
            speakerButton.classList.remove('bg-gray-200');
            speakerButton.classList.add('bg-blue-500', 'text-white');
        } else {
            speakerButton.classList.remove('bg-blue-500', 'text-white');
            speakerButton.classList.add('bg-gray-200');
        }
    }
}

function toggleHold() {
    if (!initializeElements()) {
        debug('Failed to initialize elements');
        return;
    }

    callState.isOnHold = !callState.isOnHold;
    if (holdButton) {
        if (callState.isOnHold) {
            holdButton.classList.remove('bg-gray-200');
            holdButton.classList.add('bg-yellow-500', 'text-white');
            hold();
        } else {
            holdButton.classList.remove('bg-yellow-500', 'text-white');
            holdButton.classList.add('bg-gray-200');
            unhold();
        }
    }
}

function endCall() {
    stopCallTimer();
    drop();
    hideVoiceCallPopup();
    currentCall = null;
}

function showVoiceCallPopup() {
    const popup = document.getElementById('voiceCallPopup');
    popup.classList.remove('hidden');
    popup.classList.add('flex');
    startCallTimer();
}

function hideVoiceCallPopup() {
    const popup = document.getElementById('voiceCallPopup');
    popup.classList.add('hidden');
    popup.classList.remove('flex');
}

// Function to open call window
function openCallWindow(phoneNumber) {
    // Close existing window if any
    if (callState.popupWindow && !callState.popupWindow.closed) {
        callState.popupWindow.close();
    }

    // Calculate center position
    const width = 400;
    const height = 600;
    const left = (window.screen.width - width) / 2;
    const top = 0; // Position at top of screen

    // Open new window
    callState.popupWindow = window.open(
        `/voice-call?phone=${encodeURIComponent(phoneNumber)}`,
        'voiceCall',
        `width=${width},height=${height},left=${left},top=${top},resizable=no`
    );

    // Focus the window
    if (callState.popupWindow) {
        callState.popupWindow.focus();
    }
}

// Function to show incoming call
async function showIncomingCall(phoneNumber) {
    // If in popup window, handle incoming call
    if (window.opener) {
        try {
            // Clean phone number
            const cleanPhone = phoneNumber.replace(/\D/g, '');

            // Fetch user data
            const response = await fetch(`/api/chat-ticket-users/search?phone=${cleanPhone}`);
            const data = await response.json();

            if (data && data.length > 0) {
                const user = data[0];
                document.getElementById('callerName').textContent = user.name || 'Unknown Caller';
                document.getElementById('callerNumber').textContent = phoneNumber;
                document.getElementById('activeCallerName').textContent = user.name || 'Unknown Caller';
                document.getElementById('activeCallerNumber').textContent = phoneNumber;
            } else {
                document.getElementById('callerName').textContent = 'Unknown Caller';
                document.getElementById('callerNumber').textContent = phoneNumber;
                document.getElementById('activeCallerName').textContent = 'Unknown Caller';
                document.getElementById('activeCallerNumber').textContent = phoneNumber;
            }
        } catch (error) {
            console.error('Error fetching user data:', error);
            document.getElementById('callerName').textContent = 'Unknown Caller';
            document.getElementById('callerNumber').textContent = phoneNumber;
            document.getElementById('activeCallerName').textContent = 'Unknown Caller';
            document.getElementById('activeCallerNumber').textContent = phoneNumber;
        }

        // Show incoming call notification
        document.getElementById('incomingCallNotification').classList.remove('hidden');
        callState.isRinging = true;
        playRingtone();
    } else {
        // If in parent window, open popup
        openCallWindow(phoneNumber);
    }
}

// Function to accept call
function acceptCall() {
    stopRingtone();
    document.getElementById('incomingCallNotification').classList.add('hidden');
    document.getElementById('voiceCallPopup').classList.remove('hidden');
    callState.isRinging = false;
    callState.isActive = true;
    startCallTimer();
}

// Function to reject/end call
function rejectCall() {
    stopRingtone();
    window.close();
}

function endCall() {
    stopRingtone();
    window.close();
}

// Call controls
function toggleMute() {
    callState.isMuted = !callState.isMuted;
    const muteButton = document.getElementById('muteButton');
    muteButton.classList.toggle('bg-red-500', callState.isMuted);
}

function toggleSpeaker() {
    callState.isSpeaker = !callState.isSpeaker;
    const speakerButton = document.getElementById('speakerButton');
    speakerButton.classList.toggle('bg-blue-500', callState.isSpeaker);
}

function toggleHold() {
    callState.isOnHold = !callState.isOnHold;
    const holdButton = document.getElementById('holdButton');
    if (holdButton) {
        if (callState.isOnHold) {
            holdButton.classList.add('bg-yellow-300', 'text-yellow-800');
            holdButton.classList.remove('bg-yellow-200');
        } else {
            holdButton.classList.remove('bg-yellow-300', 'text-yellow-800');
            holdButton.classList.add('bg-yellow-200');
        }
    }
}

// Call timer
function startCallTimer() {
    let seconds = 0;
    const timerElement = document.getElementById('callTimer').querySelector('span');

    callState.timerInterval = setInterval(() => {
        seconds++;
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
    }, 1000);
}

// Voice Call API Functions
function dial(phoneNumber) {
    const formattedPhone = formatPhoneNumber(phoneNumber);
    const Http = new XMLHttpRequest();
    const url = `/api/voice-call/dial/${formattedPhone}`;
    console.log("Dialing URL: " + url);

    Http.open("GET", url);
    Http.send();

    Http.onreadystatechange = (e) => {
        if (Http.readyState === 4) {
            if (Http.status === 200) {
                const response = JSON.parse(Http.responseText);
                console.log("Dial response:", response);

                if (response.status === 'success') {
                    currentCall = {
                        phoneNumber: formattedPhone,
                        userData: response.user
                    };

                    updateUserData(response.user);
                    document.getElementById('activeCallerNumber').textContent = formattedPhone;
                    showSwal('success', 'Dialing...', 'Calling ' + formattedPhone);
                    showVoiceCallPopup();
                }
            } else {
                showSwal('error', 'Error', 'Failed to dial number');
            }
        }
    };
}

function answer() {
    const urlParams = new URLSearchParams(window.location.search);
    const phoneNumber = urlParams.get('phone');

    if (!phoneNumber) {
        showSwal('error', 'Error', 'Phone number is required');
        return;
    }

    const formattedPhone = formatPhoneNumber(phoneNumber);
    const Http = new XMLHttpRequest();
    const url = `/api/voice-call/answer?phone=${formattedPhone}`;
    console.log("Answering URL: " + url);

    Http.open("GET", url);
    Http.send();

    Http.onreadystatechange = (e) => {
        if (Http.readyState === 4) {
            if (Http.status === 200) {
                const response = JSON.parse(Http.responseText);
                console.log("Answer response:", response);

                if (response.status === 'success') {
                    currentCall = {
                        phoneNumber: formattedPhone,
                        userData: response.user
                    };

                    updateUserData(response.user);
                    document.getElementById('activeCallerNumber').textContent = formattedPhone;
                    showVoiceCallPopup();
                }
            } else {
                showSwal('error', 'Error', 'Failed to answer call');
            }
        }
    };
}

function hold() {
    const Http = new XMLHttpRequest();
    const url = "/api/voice-call/hold";
    console.log("Hold URL: " + url);

    Http.open("GET", url);
    Http.send();

    Http.onreadystatechange = (e) => {
        if (Http.readyState === 4) {
            if (Http.status === 200) {
                showSwal('info', 'Call on Hold', 'The call has been placed on hold');
            } else {
                showSwal('error', 'Error', 'Failed to hold call');
            }
        }
    };
}

function unhold() {
    const Http = new XMLHttpRequest();
    const url = "/api/voice-call/unhold";
    console.log("Unhold URL: " + url);

    Http.open("GET", url);
    Http.send();

    Http.onreadystatechange = (e) => {
        if (Http.readyState === 4) {
            if (Http.status === 200) {
                showSwal('info', 'Call Resumed', 'The call has been resumed');
            } else {
                showSwal('error', 'Error', 'Failed to resume call');
            }
        }
    };
}

function drop() {
    const Http = new XMLHttpRequest();
    const url = "/api/voice-call/drop";
    console.log("Drop URL: " + url);

    Http.open("GET", url);
    Http.send();

    Http.onreadystatechange = (e) => {
        if (Http.readyState === 4) {
            if (Http.status === 200) {
                showSwal('info', 'Call Ended', 'The call has been terminated');
            } else {
                showSwal('error', 'Error', 'Failed to end call');
            }
        }
    };
}

// Make element draggable
function makeDraggable(element) {
    let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

    if (element) {
        element.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position
        element.style.top = (element.offsetTop - pos2) + "px";
        element.style.left = (element.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        // stop moving when mouse button is released
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing voice call components...');
    initializeElements();

    // Check URL parameters for incoming calls
    const urlParams = new URLSearchParams(window.location.search);
    const phoneNumber = urlParams.get('phone');
    if (phoneNumber) {
        showIncomingCall(phoneNumber);
    }

    // Test button functionality
    const testCallButton = document.getElementById('testCallButton');
    if (testCallButton) {
        testCallButton.addEventListener('click', function() {
            console.log('Test call button clicked');
            showIncomingCall('081329453873');
        });
    }
});