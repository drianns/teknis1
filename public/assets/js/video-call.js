class VideoCall {
    constructor() {
        console.log('Initializing VideoCall...');
        this.isVideo = true;
        this.isMuted = false;
        this.isCameraOn = true;
        this.isMinimized = false;
        this.isScreenSharing = false;
        this.callTimer = null;
        this.callDuration = 0;
        
        // Initialize elements
        this.elements = {
            incomingVideoCallPopup: document.getElementById('incomingVideoCallPopup'),
            videoCallPopup: document.getElementById('videoCallPopup'),
            minimizedCallBtn: document.getElementById('minimizedCallBtn'),
            ringtone: document.getElementById('videoCallRingtone'),
            localVideo: document.getElementById('localVideo'),
            remoteVideo: document.getElementById('remoteVideo'),
            callTimer: document.getElementById('callTimer'),
            micToggle: document.getElementById('micToggle'),
            cameraToggle: document.getElementById('cameraToggle'),
            callTypeToggle: document.getElementById('callTypeToggle'),
            screenShareToggle: document.getElementById('screenShareToggle'),
            videoCallHeader: document.getElementById('videoCallHeader')
        };

        // Log element status
        Object.entries(this.elements).forEach(([key, element]) => {
            console.log(`${key} exists:`, !!element);
        });

        // Initialize draggable
        this.initializeDraggable();
        
        console.log('VideoCall initialized successfully');
    }

    showIncomingCall(callerName = 'John Doe', callerAvatar = '/assets/images/users/avatar-1.jpg') {
        console.log('Showing incoming call for:', callerName);
        
        const popup = this.elements.incomingVideoCallPopup;
        const ringtone = this.elements.ringtone;
        
        if (!popup) {
            console.error('Incoming video call popup not found');
            return;
        }
        
        // Set caller info
        document.getElementById('callerName').textContent = callerName;
        document.getElementById('callerAvatar').src = callerAvatar;
        
        // Update call type indicator
        this.updateCallTypeIndicator();
        
        // Show popup with animation
        popup.classList.remove('hidden');
        setTimeout(() => popup.classList.add('show'), 10);
        
        // Play ringtone
        if (ringtone) {
            ringtone.play().catch(err => console.warn('Could not play ringtone:', err));
        }
    }

    acceptCall() {
        console.log('Accepting call...');
        
        const incomingPopup = this.elements.incomingVideoCallPopup;
        const videoPopup = this.elements.videoCallPopup;
        const ringtone = this.elements.ringtone;
        
        if (!incomingPopup || !videoPopup) {
            console.error('Required elements not found');
            return;
        }
        
        // Hide incoming call popup with animation
        incomingPopup.classList.remove('show');
        setTimeout(() => {
            incomingPopup.classList.add('hidden');
            
            // Show video call popup with animation
            videoPopup.classList.remove('hidden');
            setTimeout(() => videoPopup.classList.add('show'), 10);
        }, 300);
        
        // Stop ringtone
        if (ringtone) ringtone.pause();
        
        // Start call timer
        this.startCallTimer();
        
        // Initialize video streams
        this.initializeStreams();
    }

    declineCall() {
        console.log('Declining call...');
        
        const popup = this.elements.incomingVideoCallPopup;
        const ringtone = this.elements.ringtone;
        
        if (!popup) {
            console.error('Incoming video call popup not found');
            return;
        }
        
        // Hide popup with animation
        popup.classList.remove('show');
        setTimeout(() => popup.classList.add('hidden'), 300);
        
        // Stop ringtone
        if (ringtone) ringtone.pause();
    }

    endCall() {
        console.log('Ending call...');
        
        const popup = this.elements.videoCallPopup;
        
        if (!popup) {
            console.error('Video call popup not found');
            return;
        }
        
        // Hide popup with animation
        popup.classList.remove('show');
        setTimeout(() => popup.classList.add('hidden'), 300);
        
        // Stop timer
        this.stopCallTimer();
        
        // Clean up streams
        this.cleanupStreams();
    }

    minimizeCall() {
        const videoPopup = this.elements.videoCallPopup;
        const minimizedBtn = this.elements.minimizedCallBtn;
        
        if (!videoPopup || !minimizedBtn) {
            console.error('Required elements not found');
            return;
        }
        
        // Hide video popup with animation
        videoPopup.classList.remove('show');
        setTimeout(() => {
            videoPopup.classList.add('hidden');
            
            // Show minimized button
            minimizedBtn.classList.remove('hidden');
        }, 300);
        
        this.isMinimized = true;
    }

    maximizeCall() {
        const videoPopup = this.elements.videoCallPopup;
        const minimizedBtn = this.elements.minimizedCallBtn;
        
        if (!videoPopup || !minimizedBtn) {
            console.error('Required elements not found');
            return;
        }
        
        // Hide minimized button
        minimizedBtn.classList.add('hidden');
        
        // Show video popup with animation
        videoPopup.classList.remove('hidden');
        setTimeout(() => videoPopup.classList.add('show'), 10);
        
        this.isMinimized = false;
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
        const timerElement = this.elements.callTimer;
        if (timerElement) {
            const minutes = Math.floor(this.callDuration / 60);
            const seconds = this.callDuration % 60;
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
    }

    initializeDraggable() {
        const header = this.elements.videoCallHeader;
        const popup = this.elements.videoCallPopup;
        
        if (!header || !popup) {
            console.error('Required elements for drag functionality not found');
            return;
        }

        let isDragging = false;
        let currentX;
        let currentY;
        let initialX;
        let initialY;
        let xOffset = 0;
        let yOffset = 0;

        const dragStart = (e) => {
            if (e.type === "touchstart") {
                initialX = e.touches[0].clientX - xOffset;
                initialY = e.touches[0].clientY - yOffset;
            } else {
                initialX = e.clientX - xOffset;
                initialY = e.clientY - yOffset;
            }
            
            if (e.target.closest('.video-control-btn')) return;
            isDragging = true;
        };

        const drag = (e) => {
            if (isDragging) {
                e.preventDefault();
                
                if (e.type === "touchmove") {
                    currentX = e.touches[0].clientX - initialX;
                    currentY = e.touches[0].clientY - initialY;
                } else {
                    currentX = e.clientX - initialX;
                    currentY = e.clientY - initialY;
                }

                xOffset = currentX;
                yOffset = currentY;

                popup.style.transform = `translate(${currentX}px, ${currentY}px)`;
            }
        };

        const dragEnd = () => {
            isDragging = false;
        };

        header.addEventListener('mousedown', dragStart);
        header.addEventListener('touchstart', dragStart);
        document.addEventListener('mousemove', drag);
        document.addEventListener('touchmove', drag);
        document.addEventListener('mouseup', dragEnd);
        document.addEventListener('touchend', dragEnd);
    }

    toggleCallType() {
        this.isVideo = !this.isVideo;
        
        // Update UI elements
        this.updateCallTypeIndicator();
        
        // Toggle video tracks
        if (this.localStream) {
            const videoTrack = this.localStream.getVideoTracks()[0];
            if (videoTrack) {
                videoTrack.enabled = this.isVideo;
            }
        }
        
        // Update video elements visibility
        const localVideo = this.elements.localVideo;
        const remoteVideo = this.elements.remoteVideo;
        
        if (localVideo) localVideo.style.display = this.isVideo ? 'block' : 'none';
        if (remoteVideo) remoteVideo.style.display = this.isVideo ? 'block' : 'none';
        
        // Update button icons
        const callTypeToggle = this.elements.callTypeToggle;
        if (callTypeToggle) {
            callTypeToggle.innerHTML = `<i class="ri-${this.isVideo ? 'video-line' : 'phone-line'}"></i>`;
        }
    }

    updateCallTypeIndicator() {
        const indicator = document.getElementById('callTypeIndicator');
        if (indicator) {
            indicator.innerHTML = `
                <i class="ri-${this.isVideo ? 'video-fill' : 'phone-fill'} mr-2"></i>
                <span>${this.isVideo ? 'Video Call' : 'Voice Call'}</span>
            `;
        }
    }

    toggleMic() {
        this.isMuted = !this.isMuted;
        
        // Update button state
        const micToggle = this.elements.micToggle;
        if (micToggle) {
            micToggle.innerHTML = `<i class="ri-mic-${this.isMuted ? 'off-' : ''}line"></i>`;
            micToggle.classList.toggle('bg-red-500', this.isMuted);
        }
        
        // Mute/unmute audio track
        if (this.localStream) {
            const audioTrack = this.localStream.getAudioTracks()[0];
            if (audioTrack) {
                audioTrack.enabled = !this.isMuted;
            }
        }
    }

    toggleCamera() {
        this.isCameraOn = !this.isCameraOn;
        
        // Update button state
        const cameraToggle = this.elements.cameraToggle;
        if (cameraToggle) {
            cameraToggle.innerHTML = `<i class="ri-camera-${this.isCameraOn ? '' : 'off-'}line"></i>`;
            cameraToggle.classList.toggle('bg-red-500', !this.isCameraOn);
        }
        
        // Enable/disable video track
        if (this.localStream) {
            const videoTrack = this.localStream.getVideoTracks()[0];
            if (videoTrack) {
                videoTrack.enabled = this.isCameraOn;
            }
        }
    }

    toggleScreenShare() {
        if (!this.isVideo) return; // Only available in video calls
        
        this.isScreenSharing = !this.isScreenSharing;
        
        // Update button state
        const screenShareToggle = this.elements.screenShareToggle;
        if (screenShareToggle) {
            screenShareToggle.classList.toggle('bg-blue-500', this.isScreenSharing);
        }
        
        // Implement screen sharing logic here
        if (this.isScreenSharing) {
            navigator.mediaDevices.getDisplayMedia({ video: true })
                .then(stream => {
                    // Handle screen share stream
                    const videoTrack = stream.getVideoTracks()[0];
                    videoTrack.onended = () => this.toggleScreenShare();
                })
                .catch(error => {
                    console.error('Error sharing screen:', error);
                    this.isScreenSharing = false;
                    if (screenShareToggle) {
                        screenShareToggle.classList.remove('bg-blue-500');
                    }
                });
        }
    }

    initializeStreams() {
        navigator.mediaDevices.getUserMedia({ video: this.isVideo, audio: true })
            .then(stream => {
                this.localStream = stream;
                const localVideo = this.elements.localVideo;
                if (localVideo) {
                    localVideo.srcObject = stream;
                }
            })
            .catch(error => {
                console.error('Error accessing media devices:', error);
            });
    }

    cleanupStreams() {
        if (this.localStream) {
            this.localStream.getTracks().forEach(track => track.stop());
        }
    }
}

// Initialize video call when the script loads
console.log('Video call script loaded, creating global instance...');
window.videoCall = new VideoCall();
