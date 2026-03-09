/**
 * Incoming Call Handler with Apple-like Liquid Effect
 * This script handles the incoming call UI with dynamic liquid effects,
 * drag-and-drop functionality, and call controls.
 */

// Call state variables
let callTimer;
let callDuration = 0;
let isMuted = false;
let isSpeakerOn = false;
let isOnHold = false;
let dragOffset = { x: 0, y: 0 };
let activeCall = null;
let isMobile = false;

// Debug flag
const DEBUG = true;

// Initialize the call component
document.addEventListener('DOMContentLoaded', function() {
    if (DEBUG) console.log('Incoming Call JS Loaded');
    
    // Check if device is mobile
    isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    setupDraggable();
    setupLiquidEffect();
    setupButtonEffects();
    
    // Add viewport meta tag for mobile if not present
    if (isMobile && !document.querySelector('meta[name="viewport"]')) {
        const meta = document.createElement('meta');
        meta.name = 'viewport';
        meta.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no';
        document.getElementsByTagName('head')[0].appendChild(meta);
    }
    
    // Expose test function to global scope
    window.testIncomingCall = testIncomingCall;
    
    // Debug check for elements
    if (DEBUG) {
        const popup = document.getElementById('incomingCallPopup');
        const ringtone = document.getElementById('ringtone');
        console.log('Popup element found:', !!popup);
        console.log('Ringtone element found:', !!ringtone);
    }
});

/**
 * Setup draggable functionality for the call popup
 */
function setupDraggable() {
    const popup = document.getElementById('incomingCallPopup');
    if (!popup) {
        if (DEBUG) console.error('Popup element not found in setupDraggable');
        return;
    }
    
    // Mouse events for desktop
    popup.addEventListener('mousedown', function(e) {
        // Only start drag if clicking on the header area, not on buttons
        if (e.target.closest('button')) return;
        
        startDrag(e.clientX, e.clientY);
        
        document.addEventListener('mousemove', onMouseDrag);
        document.addEventListener('mouseup', stopMouseDrag);
    });
    
    // Touch events for mobile
    popup.addEventListener('touchstart', function(e) {
        // Only start drag if touching the header area, not on buttons
        if (e.target.closest('button')) return;
        
        const touch = e.touches[0];
        startDrag(touch.clientX, touch.clientY);
        
        document.addEventListener('touchmove', onTouchDrag, { passive: false });
        document.addEventListener('touchend', stopTouchDrag);
        
        // Prevent default to avoid scrolling while dragging
        e.preventDefault();
    }, { passive: false });
    
    function startDrag(clientX, clientY) {
        const rect = popup.getBoundingClientRect();
        dragOffset.x = clientX - rect.left;
        dragOffset.y = clientY - rect.top;
        
        popup.classList.add('dragging');
    }
    
    function onMouseDrag(e) {
        moveDrag(e.clientX, e.clientY, e.movementX, e.movementY);
    }
    
    function onTouchDrag(e) {
        const touch = e.touches[0];
        
        // Calculate movement
        const rect = popup.getBoundingClientRect();
        const movementX = touch.clientX - (rect.left + dragOffset.x);
        const movementY = touch.clientY - (rect.top + dragOffset.y);
        
        moveDrag(touch.clientX, touch.clientY, movementX, movementY);
        
        // Prevent default to avoid scrolling while dragging
        e.preventDefault();
    }
    
    function moveDrag(clientX, clientY, movementX, movementY) {
        // Keep the popup within the viewport
        const rect = popup.getBoundingClientRect();
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        
        let left = clientX - dragOffset.x;
        let top = clientY - dragOffset.y;
        
        // Boundary checks
        if (left < 0) left = 0;
        if (top < 0) top = 0;
        if (left + rect.width > viewportWidth) left = viewportWidth - rect.width;
        if (top + rect.height > viewportHeight) top = viewportHeight - rect.height;
        
        popup.style.left = left + 'px';
        popup.style.top = top + 'px';
        popup.style.right = 'auto'; // Clear the right position when dragging
        popup.style.transform = 'none'; // Clear any transform when dragging
        popup.style.margin = '0'; // Clear any margin when dragging
        
        // Move blobs slightly in the opposite direction of drag for a fluid effect
        const blobs = document.querySelectorAll('.blob');
        const dragDirection = {
            x: movementX * -0.1,
            y: movementY * -0.1
        };
        
        blobs.forEach((blob, index) => {
            const factor = 1 - (index * 0.2);
            blob.style.transform = `translate(${dragDirection.x * factor}px, ${dragDirection.y * factor}px)`;
        });
    }
    
    function stopMouseDrag() {
        document.removeEventListener('mousemove', onMouseDrag);
        document.removeEventListener('mouseup', stopMouseDrag);
        finishDrag();
    }
    
    function stopTouchDrag() {
        document.removeEventListener('touchmove', onTouchDrag);
        document.removeEventListener('touchend', stopTouchDrag);
        finishDrag();
    }
    
    function finishDrag() {
        popup.classList.remove('dragging');
        
        // Reset blob positions with animation
        const blobs = document.querySelectorAll('.blob');
        blobs.forEach(blob => {
            blob.style.transition = 'transform 0.5s ease-out';
            blob.style.transform = '';
            setTimeout(() => {
                blob.style.transition = '';
            }, 500);
        });
    }
}

/**
 * Setup advanced liquid effect for the call popup
 */
function setupLiquidEffect() {
    const liquidEffect = document.getElementById('liquidEffect');
    if (!liquidEffect) {
        if (DEBUG) console.error('Liquid effect element not found');
        return;
    }
    
    // Reduce number of blobs on mobile for better performance
    const blobCount = isMobile ? 2 : 3;
    
    // Create more dynamic blobs programmatically
    for (let i = 4; i <= 3 + blobCount; i++) {
        const blob = document.createElement('div');
        blob.className = `blob blob-${i}`;
        blob.style.width = `${70 + Math.random() * 50}px`;
        blob.style.height = blob.style.width;
        blob.style.background = getRandomColor(0.4);
        blob.style.top = `${Math.random() * 100}%`;
        blob.style.left = `${Math.random() * 100}%`;
        blob.style.animationDelay = `${-Math.random() * 15}s`;
        liquidEffect.appendChild(blob);
    }
}

/**
 * Setup button effects including ripple
 */
function setupButtonEffects() {
    const buttons = document.querySelectorAll('#incomingCallPopup button');
    if (!buttons.length) {
        if (DEBUG) console.error('No buttons found in setupButtonEffects');
        return;
    }
    
    buttons.forEach(button => {
        // Use click for desktop and touchstart for mobile
        button.addEventListener('click', createRippleEffect);
        button.addEventListener('touchstart', handleTouchStart, { passive: false });
        
        // Add hover effect that influences the blobs (desktop only)
        if (!isMobile) {
            button.addEventListener('mouseenter', () => {
                const liquidEffect = document.getElementById('liquidEffect');
                if (!liquidEffect) return;
                
                const blobs = liquidEffect.querySelectorAll('.blob');
                blobs.forEach(blob => {
                    // Slightly move blobs towards the hovered button
                    const buttonRect = button.getBoundingClientRect();
                    const liquidRect = liquidEffect.getBoundingClientRect();
                    
                    const buttonCenter = {
                        x: buttonRect.left + buttonRect.width / 2 - liquidRect.left,
                        y: buttonRect.top + buttonRect.height / 2 - liquidRect.top
                    };
                    
                    const blobCenter = {
                        x: parseFloat(blob.style.left) || 0,
                        y: parseFloat(blob.style.top) || 0
                    };
                    
                    const direction = {
                        x: (buttonCenter.x - blobCenter.x) * 0.05,
                        y: (buttonCenter.y - blobCenter.y) * 0.05
                    };
                    
                    blob.style.transform = `translate(${direction.x}px, ${direction.y}px) scale(1.1)`;
                });
            });
            
            button.addEventListener('mouseleave', () => {
                const blobs = document.querySelectorAll('.blob');
                blobs.forEach(blob => {
                    blob.style.transform = '';
                });
            });
        }
    });
    
    function handleTouchStart(e) {
        // Create ripple effect on touch
        createRippleEffect(e);
        
        // Prevent default behavior only if needed
        if (e.target.closest('.draggable-area')) {
            e.preventDefault();
        }
    }
}

/**
 * Create ripple effect on button click
 */
function createRippleEffect(event) {
    const button = event.currentTarget;
    
    // Remove any existing ripples
    const existingRipple = button.querySelector('.ripple');
    if (existingRipple) {
        existingRipple.remove();
    }
    
    // Create ripple element
    const ripple = document.createElement('span');
    ripple.classList.add('ripple');
    button.appendChild(ripple);
    
    // Position the ripple
    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height) * 1.5;
    
    // Get position based on event type
    let clientX, clientY;
    
    if (event.touches) { // Touch event
        clientX = event.touches[0].clientX;
        clientY = event.touches[0].clientY;
    } else { // Mouse event
        clientX = event.clientX;
        clientY = event.clientY;
    }
    
    ripple.style.width = ripple.style.height = `${size}px`;
    ripple.style.left = `${clientX - rect.left - size / 2}px`;
    ripple.style.top = `${clientY - rect.top - size / 2}px`;
    
    // Remove ripple after animation completes
    setTimeout(() => {
        ripple.remove();
    }, 600);
}

/**
 * Show the incoming call popup with caller information
 */
function showIncomingCall(name, number, avatar, callId) {
    if (DEBUG) console.log('showIncomingCall called with:', { name, number, avatar, callId });
    
    const popup = document.getElementById('incomingCallPopup');
    if (!popup) {
        console.error('Popup element not found in showIncomingCall');
        return;
    }
    
    const ringtone = document.getElementById('ringtone');
    if (!ringtone) {
        console.error('Ringtone element not found');
    }
    
    const callStatus = document.getElementById('callStatus');
    if (!callStatus) {
        console.error('Call status element not found');
    }
    
    // Store call ID for reference
    activeCall = callId || Date.now().toString();
    
    // Set caller info
    const callerName = document.getElementById('callerName');
    const callerNumber = document.getElementById('callerNumber');
    const callerAvatar = document.getElementById('callerAvatar');
    
    if (callerName) callerName.textContent = name || 'Unknown Caller';
    if (callerNumber) callerNumber.textContent = number || '';
    if (callerAvatar) {
        // Make sure the avatar path is correct
        const baseUrl = window.location.origin;
        let avatarPath = avatar || '/assets/images/users/avatar-1.jpg';
        
        // If the path doesn't start with http or /, add the base URL
        if (!avatarPath.startsWith('http') && !avatarPath.startsWith('/')) {
            avatarPath = '/' + avatarPath;
        }
        
        callerAvatar.src = avatarPath;
        
        // Log the avatar path for debugging
        if (DEBUG) console.log('Avatar path:', callerAvatar.src);
    }
    
    // Reset call state
    isMuted = false;
    isSpeakerOn = false;
    isOnHold = false;
    
    if (callStatus) {
        callStatus.textContent = 'Incoming Call';
        callStatus.classList.remove('text-yellow-400', 'text-blue-400');
        callStatus.classList.add('text-green-400');
    }
    
    // Reset UI elements
    const muteButton = document.getElementById('muteButton');
    const speakerButton = document.getElementById('speakerButton');
    const holdButton = document.getElementById('holdButton');
    
    if (muteButton) {
        const muteIcon = muteButton.querySelector('i');
        const muteDiv = muteButton.querySelector('div');
        
        if (muteIcon) {
            muteIcon.classList.add('fa-microphone');
            muteIcon.classList.remove('fa-microphone-slash');
        }
        
        if (muteDiv) {
            muteDiv.classList.remove('bg-red-500');
            muteDiv.classList.add('bg-gray-600');
        }
    }
    
    if (speakerButton) {
        const speakerDiv = speakerButton.querySelector('div');
        if (speakerDiv) {
            speakerDiv.classList.remove('bg-blue-500');
            speakerDiv.classList.add('bg-gray-600');
        }
    }
    
    if (holdButton) {
        const holdDiv = holdButton.querySelector('div');
        if (holdDiv) {
            holdDiv.classList.remove('bg-yellow-500');
            holdDiv.classList.add('bg-gray-600');
        }
    }
    
    // Remove any state classes
    popup.classList.remove('call-active', 'call-muted', 'call-hold');
    
    // Force any inline styles to be removed
    popup.removeAttribute('style');
    
    // Show popup with drop-in animation
    popup.classList.remove('hidden', 'scale-95', 'opacity-0');
    popup.classList.add('scale-100', 'opacity-100');
    
    // Add drop-in animation class
    popup.classList.add('drop-in');
    
    // Force reflow to ensure styles are applied
    void popup.offsetWidth;
    
    // Ensure the popup is visible with inline styles
    popup.style.display = 'block';
    popup.style.opacity = '1';
    popup.style.visibility = 'visible';
    
    // Animate blobs
    animateBlobs();
    
    // Play ringtone
    if (ringtone) {
        ringtone.play().catch(error => {
            console.log('Autoplay prevented:', error);
            // Try to play after user interaction
            document.addEventListener('click', function playOnClick() {
                ringtone.play().catch(e => console.log('Still cannot play:', e));
                document.removeEventListener('click', playOnClick);
            }, { once: true });
        });
    }
    
    // Auto-reject call after 30 seconds if not answered
    setTimeout(() => {
        if (!popup.classList.contains('hidden') && 
            document.getElementById('activeCallControls').classList.contains('hidden')) {
            rejectCall();
        }
    }, 30000);
    
    // Vibrate on mobile devices if supported
    if (isMobile && 'vibrate' in navigator) {
        // Vibrate pattern: 500ms vibration, 200ms pause, 500ms vibration
        navigator.vibrate([500, 200, 500]);
    }
    
    // Log that the popup should be visible now
    console.log('Incoming call popup should be visible now');
}

/**
 * Animate the liquid effect blobs
 */
function animateBlobs() {
    const blobs = document.querySelectorAll('.blob');
    blobs.forEach(blob => {
        // The animation is handled by CSS, but we can add some randomness here
        const scale = 0.8 + Math.random() * 0.4;
        blob.style.transform = `scale(${scale})`;
    });
}

/**
 * Accept the incoming call
 */
function acceptCall() {
    if (DEBUG) console.log('acceptCall called');
    
    const popup = document.getElementById('incomingCallPopup');
    if (!popup) {
        console.error('Popup element not found in acceptCall');
        return;
    }
    
    const ringtone = document.getElementById('ringtone');
    const timer = document.getElementById('callTimer');
    const callStatus = document.getElementById('callStatus');
    
    // Stop ringtone
    if (ringtone) {
        ringtone.pause();
        ringtone.currentTime = 0;
    }
    
    // Stop vibration if applicable
    if (isMobile && 'vibrate' in navigator) {
        navigator.vibrate(0); // Stop vibration
    }
    
    // Show timer
    if (timer) {
        timer.classList.remove('hidden');
        callDuration = 0;
        
        // Start timer
        callTimer = setInterval(() => {
            callDuration++;
            const minutes = Math.floor(callDuration / 60);
            const seconds = callDuration % 60;
            timer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }
    
    // Update UI
    if (callStatus) {
        callStatus.textContent = 'Connected';
    }
    
    const incomingControls = document.getElementById('incomingCallControls');
    const activeControls = document.getElementById('activeCallControls');
    
    if (incomingControls) incomingControls.classList.add('hidden');
    if (activeControls) activeControls.classList.remove('hidden');
    
    // Add active call class for enhanced blob effect
    popup.classList.add('call-active');
    
    // Here you would add actual call acceptance logic
    console.log('Call accepted:', activeCall);
    
    // Trigger a custom event that can be listened to by other parts of the application
    document.dispatchEvent(new CustomEvent('callAccepted', { 
        detail: { callId: activeCall, timestamp: new Date() }
    }));
}

/**
 * Reject the incoming call
 */
function rejectCall() {
    if (DEBUG) console.log('rejectCall called');
    
    const popup = document.getElementById('incomingCallPopup');
    if (!popup) {
        console.error('Popup element not found in rejectCall');
        return;
    }
    
    const ringtone = document.getElementById('ringtone');
    
    // Stop ringtone
    if (ringtone) {
        ringtone.pause();
        ringtone.currentTime = 0;
    }
    
    // Stop vibration if applicable
    if (isMobile && 'vibrate' in navigator) {
        navigator.vibrate(0); // Stop vibration
    }
    
    // Stop timer if running
    if (callTimer) {
        clearInterval(callTimer);
        callTimer = null;
    }
    
    // Here you would add actual call rejection logic
    console.log('Call rejected:', activeCall);
    
    // Trigger a custom event that can be listened to by other parts of the application
    document.dispatchEvent(new CustomEvent('callRejected', { 
        detail: { callId: activeCall, timestamp: new Date() }
    }));
    
    // Hide popup with animation
    popup.classList.add('scale-95', 'opacity-0');
    popup.classList.remove('drop-in');
    
    setTimeout(() => {
        popup.classList.add('hidden');
        
        const timer = document.getElementById('callTimer');
        const incomingControls = document.getElementById('incomingCallControls');
        const activeControls = document.getElementById('activeCallControls');
        
        if (timer) timer.classList.add('hidden');
        if (incomingControls) incomingControls.classList.remove('hidden');
        if (activeControls) activeControls.classList.add('hidden');
        
        popup.classList.remove('call-active', 'call-muted', 'call-hold');
        activeCall = null;
    }, 300);
}

/**
 * End the active call
 */
function endCall() {
    if (DEBUG) console.log('endCall called');
    
    // Here you would add actual call ending logic
    console.log('Call ended:', activeCall);
    
    // Trigger a custom event that can be listened to by other parts of the application
    document.dispatchEvent(new CustomEvent('callEnded', { 
        detail: { callId: activeCall, duration: callDuration, timestamp: new Date() }
    }));
    
    rejectCall(); // Reuse the reject call logic
}

/**
 * Toggle mute state
 */
function toggleMute() {
    if (DEBUG) console.log('toggleMute called');
    
    isMuted = !isMuted;
    const muteButton = document.getElementById('muteButton');
    if (!muteButton) {
        console.error('Mute button not found');
        return;
    }
    
    const icon = muteButton.querySelector('i');
    const popup = document.getElementById('incomingCallPopup');
    
    if (isMuted) {
        if (icon) {
            icon.classList.remove('fa-microphone');
            icon.classList.add('fa-microphone-slash');
        }
        
        const buttonDiv = muteButton.querySelector('div');
        if (buttonDiv) {
            buttonDiv.classList.add('bg-red-500');
            buttonDiv.classList.remove('bg-gray-600');
        }
        
        if (popup) popup.classList.add('call-muted');
    } else {
        if (icon) {
            icon.classList.add('fa-microphone');
            icon.classList.remove('fa-microphone-slash');
        }
        
        const buttonDiv = muteButton.querySelector('div');
        if (buttonDiv) {
            buttonDiv.classList.remove('bg-red-500');
            buttonDiv.classList.add('bg-gray-600');
        }
        
        if (popup) popup.classList.remove('call-muted');
    }
    
    // Here you would add actual mute functionality for the call
    console.log('Mute toggled:', isMuted);
    
    // Trigger a custom event
    document.dispatchEvent(new CustomEvent('callMuteToggled', { 
        detail: { callId: activeCall, isMuted: isMuted }
    }));
    
    // Provide haptic feedback on mobile if supported
    if (isMobile && 'vibrate' in navigator) {
        navigator.vibrate(50); // Short vibration for feedback
    }
}

/**
 * Toggle speaker state
 */
function toggleSpeaker() {
    if (DEBUG) console.log('toggleSpeaker called');
    
    isSpeakerOn = !isSpeakerOn;
    const speakerButton = document.getElementById('speakerButton');
    if (!speakerButton) {
        console.error('Speaker button not found');
        return;
    }
    
    const buttonDiv = speakerButton.querySelector('div');
    if (buttonDiv) {
        if (isSpeakerOn) {
            buttonDiv.classList.add('bg-blue-500');
            buttonDiv.classList.remove('bg-gray-600');
        } else {
            buttonDiv.classList.remove('bg-blue-500');
            buttonDiv.classList.add('bg-gray-600');
        }
    }
    
    // Here you would add actual speaker functionality for the call
    console.log('Speaker toggled:', isSpeakerOn);
    
    // Trigger a custom event
    document.dispatchEvent(new CustomEvent('callSpeakerToggled', { 
        detail: { callId: activeCall, isSpeakerOn: isSpeakerOn }
    }));
    
    // Provide haptic feedback on mobile if supported
    if (isMobile && 'vibrate' in navigator) {
        navigator.vibrate(50); // Short vibration for feedback
    }
}

/**
 * Toggle hold state
 */
function toggleHold() {
    if (DEBUG) console.log('toggleHold called');
    
    isOnHold = !isOnHold;
    const holdButton = document.getElementById('holdButton');
    if (!holdButton) {
        console.error('Hold button not found');
        return;
    }
    
    const callStatus = document.getElementById('callStatus');
    const popup = document.getElementById('incomingCallPopup');
    
    const buttonDiv = holdButton.querySelector('div');
    if (buttonDiv) {
        if (isOnHold) {
            buttonDiv.classList.add('bg-yellow-500');
            buttonDiv.classList.remove('bg-gray-600');
            
            if (callStatus) {
                callStatus.textContent = 'On Hold';
                callStatus.classList.remove('text-green-400');
                callStatus.classList.add('text-yellow-400');
            }
            
            if (popup) popup.classList.add('call-hold');
        } else {
            buttonDiv.classList.remove('bg-yellow-500');
            buttonDiv.classList.add('bg-gray-600');
            
            if (callStatus) {
                callStatus.textContent = 'Connected';
                callStatus.classList.remove('text-yellow-400');
                callStatus.classList.add('text-green-400');
            }
            
            if (popup) popup.classList.remove('call-hold');
        }
    }
    
    // Here you would add actual hold functionality for the call
    console.log('Hold toggled:', isOnHold);
    
    // Trigger a custom event
    document.dispatchEvent(new CustomEvent('callHoldToggled', { 
        detail: { callId: activeCall, isOnHold: isOnHold }
    }));
    
    // Provide haptic feedback on mobile if supported
    if (isMobile && 'vibrate' in navigator) {
        navigator.vibrate(50); // Short vibration for feedback
    }
}

/**
 * Test the incoming call functionality
 */
function testIncomingCall() {
    console.log('Test Incoming Call button clicked');
    
    // Use the full URL for the avatar to avoid path issues
    const baseUrl = window.location.origin;
    const avatarUrl = `${baseUrl}/assets/images/users/avatar-2.jpg`;
    
    showIncomingCall(
        'Test Call',
        '+62812345678',
        avatarUrl,
        'test-call-' + Date.now()
    );
}

// Make functions globally available
window.showIncomingCall = showIncomingCall;
window.acceptCall = acceptCall;
window.rejectCall = rejectCall;
window.endCall = endCall;
window.toggleMute = toggleMute;
window.toggleSpeaker = toggleSpeaker;
window.toggleHold = toggleHold;
window.testIncomingCall = testIncomingCall;

// Listen for custom events from other parts of the application
document.addEventListener('incomingCall', function(e) {
    const { name, number, avatar, callId } = e.detail;
    showIncomingCall(name, number, avatar, callId);
});

class IncomingCall {
    constructor() {
        console.log('Initializing IncomingCall...');
        this.isActive = false;
        this.isMuted = false;
        this.isSpeakerOn = false;
        this.callTimer = null;
        this.callDuration = 0;
        
        // Initialize elements
        this.elements = {
            incomingPopup: document.getElementById('incomingCallPopup'),
            activeCard: document.getElementById('activeCallCard'),
            ringtone: document.getElementById('callRingtone'),
            callerName: document.getElementById('callerName'),
            callerAvatar: document.getElementById('callerAvatar'),
            activeCallerName: document.getElementById('activeCallerName'),
            activeCallerAvatar: document.getElementById('activeCallerAvatar'),
            activeCallTimer: document.getElementById('activeCallTimer'),
            muteButton: document.getElementById('muteButton'),
            speakerButton: document.getElementById('speakerButton')
        };

        // Log element status
        Object.entries(this.elements).forEach(([key, element]) => {
            console.log(`${key} exists:`, !!element);
        });
        
        console.log('IncomingCall initialized successfully');
    }

    showIncomingCall(callerName = 'Unknown Caller', callerAvatar = '/assets/images/users/avatar-1.jpg') {
        console.log('Showing incoming call for:', callerName);
        
        if (!this.elements.incomingPopup || !this.elements.callerName || !this.elements.callerAvatar) {
            console.error('Required elements not found');
            return;
        }

        // Update caller info
        this.elements.callerName.textContent = callerName;
        this.elements.callerAvatar.src = callerAvatar;

        // Show popup with animation
        this.elements.incomingPopup.classList.remove('hidden');
        requestAnimationFrame(() => {
            this.elements.incomingPopup.classList.add('show');
        });

        // Play ringtone
        if (this.elements.ringtone) {
            this.elements.ringtone.play().catch(err => console.warn('Could not play ringtone:', err));
        }

        this.isActive = true;
    }

    acceptCall() {
        console.log('Accepting call...');
        
        if (!this.elements.incomingPopup || !this.elements.activeCard) {
            console.error('Required elements not found');
            return;
        }

        // Stop ringtone
        if (this.elements.ringtone) {
            this.elements.ringtone.pause();
            this.elements.ringtone.currentTime = 0;
        }

        // Transfer caller info to active card
        if (this.elements.activeCallerName && this.elements.callerName) {
            this.elements.activeCallerName.textContent = this.elements.callerName.textContent;
        }
        if (this.elements.activeCallerAvatar && this.elements.callerAvatar) {
            this.elements.activeCallerAvatar.src = this.elements.callerAvatar.src;
        }

        // Hide incoming popup with animation
        this.elements.incomingPopup.classList.remove('show');
        setTimeout(() => {
            this.elements.incomingPopup.classList.add('hidden');
            
            // Show active call card with animation
            this.elements.activeCard.classList.remove('hidden');
            requestAnimationFrame(() => {
                this.elements.activeCard.classList.add('show');
            });
        }, 300);

        // Start call timer
        this.startCallTimer();
    }

    declineCall() {
        console.log('Declining call...');
        
        if (!this.elements.incomingPopup) {
            console.error('Required elements not found');
            return;
        }

        // Stop ringtone
        if (this.elements.ringtone) {
            this.elements.ringtone.pause();
            this.elements.ringtone.currentTime = 0;
        }

        // Hide popup with animation
        this.elements.incomingPopup.classList.remove('show');
        setTimeout(() => {
            this.elements.incomingPopup.classList.add('hidden');
        }, 300);

        this.isActive = false;
    }

    endCall() {
        console.log('Ending call...');
        
        if (!this.elements.activeCard) {
            console.error('Required elements not found');
            return;
        }

        // Hide active call card with animation
        this.elements.activeCard.classList.remove('show');
        setTimeout(() => {
            this.elements.activeCard.classList.add('hidden');
        }, 300);

        // Stop timer
        this.stopCallTimer();
        this.isActive = false;
    }

    toggleMute() {
        this.isMuted = !this.isMuted;
        console.log('Mute toggled:', this.isMuted);
        
        if (this.elements.muteButton) {
            const icon = this.elements.muteButton.querySelector('i');
            if (icon) {
                icon.className = this.isMuted ? 'fas fa-microphone-slash text-red-500 text-lg mb-1' : 'fas fa-microphone text-gray-300 text-lg mb-1';
            }
            this.elements.muteButton.classList.toggle('bg-red-500/10', this.isMuted);
            this.elements.muteButton.classList.toggle('bg-gray-800/50', !this.isMuted);
        }
    }

    toggleSpeaker() {
        this.isSpeakerOn = !this.isSpeakerOn;
        console.log('Speaker toggled:', this.isSpeakerOn);
        
        if (this.elements.speakerButton) {
            const icon = this.elements.speakerButton.querySelector('i');
            if (icon) {
                icon.className = this.isSpeakerOn ? 'fas fa-volume-up text-blue-500 text-lg mb-1' : 'fas fa-volume-up text-gray-300 text-lg mb-1';
            }
            this.elements.speakerButton.classList.toggle('bg-blue-500/10', this.isSpeakerOn);
            this.elements.speakerButton.classList.toggle('bg-gray-800/50', !this.isSpeakerOn);
        }
    }

    startCallTimer() {
        this.callDuration = 0;
        this.callTimer = setInterval(() => {
            this.callDuration++;
            this.updateCallTimer();
        }, 1000);
    }

    stopCallTimer() {
        if (this.callTimer) {
            clearInterval(this.callTimer);
            this.callTimer = null;
        }
    }

    updateCallTimer() {
        if (this.elements.activeCallTimer) {
            const minutes = Math.floor(this.callDuration / 60);
            const seconds = this.callDuration % 60;
            this.elements.activeCallTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
    }
}

// Initialize incoming call when the script loads
console.log('Incoming call script loaded, creating global instance...');
window.incomingCall = new IncomingCall();