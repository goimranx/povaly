@extends('core/base::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cloud-download-alt"></i> System Update Manager
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Current Version Info -->
                    <div class="alert alert-info" id="version-info">
                        <h5><i class="fas fa-info-circle"></i> Current Version</h5>
                        <div id="version-details">
                            <p><i class="fas fa-spinner fa-spin"></i> Loading version information...</p>
                        </div>
                    </div>

                    <!-- Update Status -->
                    <div class="alert alert-secondary" id="update-status" style="display:none;">
                        <h5><i class="fas fa-sync-alt"></i> Update Status</h5>
                        <div id="update-check-result"></div>
                    </div>

                    <!-- Control Buttons -->
                    <div class="mb-4">
                        <button type="button" class="btn btn-primary btn-lg" id="check-updates-btn">
                            <i class="fas fa-search"></i> Check for Updates
                        </button>
                        <button type="button" class="btn btn-success btn-lg" id="update-btn" disabled>
                            <i class="fas fa-cloud-download-alt"></i> Update Now
                        </button>
                        <button type="button" class="btn btn-danger btn-lg" id="force-update-btn">
                            <i class="fas fa-bolt"></i> Force Update
                        </button>
                        <button type="button" class="btn btn-warning btn-lg" id="clear-cache-btn">
                            <i class="fas fa-broom"></i> Clear Cache Only
                        </button>
                    </div>

                    <!-- Progress Section -->
                    <div class="card" id="progress-section" style="display:none;">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-terminal"></i> Update Progress
                            </h5>
                        </div>
                        <div class="card-body bg-dark">
                            <pre id="update-output" class="text-white mb-0" style="max-height: 500px; overflow-y: auto;"></pre>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="alert alert-warning mt-4">
                        <h5><i class="fas fa-exclamation-triangle"></i> Important Notes</h5>
                        <ul class="mb-0">
                            <li>The site will go into maintenance mode during the update</li>
                            <li>Make sure you have a database backup before updating</li>
                            <li>The update process may take 2-5 minutes</li>
                            <li>Don't close this page until the update is complete</li>
                            <li><strong>Force Update</strong> will update even if no changes are detected</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #update-output {
        font-family: 'Courier New', monospace;
        font-size: 12px;
        line-height: 1.4;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    
    .btn-lg {
        padding: 10px 20px;
        margin-right: 10px;
        margin-bottom: 10px;
    }
    
    .progress-line {
        color: #00ff00;
    }
    
    .error-line {
        color: #ff4444;
    }
    
    .success-line {
        color: #00ff00;
        font-weight: bold;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let hasUpdates = false;

    // Load current version on page load
    loadVersionInfo();

    // Check for updates button
    document.getElementById('check-updates-btn').addEventListener('click', function() {
        checkForUpdates();
    });

    // Update button
    document.getElementById('update-btn').addEventListener('click', function() {
        if (confirm('Are you sure you want to update the system? The site will be put into maintenance mode.')) {
            performUpdate(false);
        }
    });

    // Force update button
    document.getElementById('force-update-btn').addEventListener('click', function() {
        if (confirm('⚠️ FORCE UPDATE will pull and apply changes even if Git shows no updates.\n\nThis is useful when:\n- You made manual changes and want to override them\n- Git is out of sync\n- You want to rebuild everything\n\nContinue?')) {
            performUpdate(true);
        }
    });

    // Clear cache button
    document.getElementById('clear-cache-btn').addEventListener('click', function() {
        if (confirm('This will clear all application caches. Continue?')) {
            clearCacheOnly();
        }
    });

    function loadVersionInfo() {
        fetch('{{ route('system.update.version') }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('version-details').innerHTML = `
                        <p class="mb-1"><strong>Commit:</strong> ${data.version.commit}</p>
                        <p class="mb-1"><strong>Branch:</strong> ${data.version.branch}</p>
                        <p class="mb-0"><strong>Last Update:</strong> ${data.version.last_update}</p>
                    `;
                } else {
                    document.getElementById('version-details').innerHTML = `
                        <p class="text-danger"><i class="fas fa-exclamation-circle"></i> ${data.message}</p>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('version-details').innerHTML = `
                    <p class="text-danger"><i class="fas fa-exclamation-circle"></i> Error loading version: ${error.message}</p>
                `;
            });
    }

    function checkForUpdates() {
        const btn = document.getElementById('check-updates-btn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';

        fetch('{{ route('system.update.check') }}')
            .then(response => response.json())
            .then(data => {
                const statusDiv = document.getElementById('update-status');
                const resultDiv = document.getElementById('update-check-result');
                
                statusDiv.style.display = 'block';

                if (data.success) {
                    hasUpdates = data.has_updates;

                    if (data.has_updates) {
                        statusDiv.className = 'alert alert-warning';
                        let changesHtml = '<p><strong>New updates available!</strong></p>';
                        changesHtml += '<p>Current: <code>' + data.current_commit + '</code> → Latest: <code>' + data.latest_commit + '</code></p>';
                        
                        if (data.changes && data.changes.length > 0) {
                            changesHtml += '<p><strong>Recent changes:</strong></p><ul>';
                            data.changes.forEach(change => {
                                changesHtml += '<li>' + change + '</li>';
                            });
                            changesHtml += '</ul>';
                        }
                        
                        resultDiv.innerHTML = changesHtml;
                        document.getElementById('update-btn').disabled = false;
                    } else {
                        statusDiv.className = 'alert alert-success';
                        resultDiv.innerHTML = `
                            <p><i class="fas fa-check-circle"></i> <strong>Your system is up to date!</strong></p>
                            <p>Current version: <code>${data.current_commit}</code></p>
                        `;
                        document.getElementById('update-btn').disabled = true;
                    }
                } else {
                    statusDiv.className = 'alert alert-danger';
                    resultDiv.innerHTML = `
                        <p><i class="fas fa-exclamation-circle"></i> <strong>Error checking for updates:</strong></p>
                        <p>${data.message}</p>
                    `;
                }

                btn.disabled = false;
                btn.innerHTML = originalText;
            })
            .catch(error => {
                const statusDiv = document.getElementById('update-status');
                statusDiv.style.display = 'block';
                statusDiv.className = 'alert alert-danger';
                document.getElementById('update-check-result').innerHTML = `
                    <p><i class="fas fa-exclamation-circle"></i> <strong>Error:</strong> ${error.message}</p>
                `;
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
    }

    function performUpdate(force = false) {
        // Disable all buttons
        document.getElementById('check-updates-btn').disabled = true;
        document.getElementById('update-btn').disabled = true;
        document.getElementById('force-update-btn').disabled = true;
        document.getElementById('clear-cache-btn').disabled = true;

        // Show progress section
        const progressSection = document.getElementById('progress-section');
        const outputDiv = document.getElementById('update-output');
        progressSection.style.display = 'block';
        outputDiv.innerHTML = '⏳ Connecting to update service...\n';

        // Scroll to progress section
        progressSection.scrollIntoView({ behavior: 'smooth' });

        // Create EventSource for Server-Sent Events (using GET with query parameter)
        const url = '{{ route('system.update.perform') }}' + (force ? '?force=1' : '');
        const eventSource = new EventSource(url);
        
        eventSource.onmessage = function(event) {
            try {
                const data = JSON.parse(event.data);
                
                if (data.type === 'complete') {
                    // Update completed or failed
                    eventSource.close();
                    
                    if (data.success) {
                        outputDiv.innerHTML += '\n\n✅ UPDATE COMPLETED SUCCESSFULLY!\n';
                        outputDiv.innerHTML += '\n🔄 Reloading page in 3 seconds...\n';
                        
                        // Auto-scroll to bottom
                        outputDiv.scrollTop = outputDiv.scrollHeight;
                        
                        // Reload page after 3 seconds
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    } else {
                        outputDiv.innerHTML += '\n\n❌ UPDATE FAILED!\n';
                        if (data.error) {
                            outputDiv.innerHTML += '\nError: ' + data.error + '\n';
                        }
                        
                        // Auto-scroll to bottom
                        outputDiv.scrollTop = outputDiv.scrollHeight;
                        
                        // Re-enable buttons
                        enableAllButtons();
                    }
                } else {
                    // Progress message
                    const line = formatLine(data.message, data.type);
                    outputDiv.innerHTML += line + '\n';
                    
                    // Auto-scroll to bottom
                    outputDiv.scrollTop = outputDiv.scrollHeight;
                }
            } catch (e) {
                console.error('Error parsing SSE data:', e);
            }
        };
        
        eventSource.onerror = function(error) {
            console.error('EventSource error:', error);
            eventSource.close();
            
            outputDiv.innerHTML += '\n\n❌ CONNECTION ERROR!\n';
            outputDiv.innerHTML += '\nThe connection to the server was lost.\n';
            outputDiv.innerHTML += '\nPlease check the server logs for details.\n';
            
            // Auto-scroll to bottom
            outputDiv.scrollTop = outputDiv.scrollHeight;
            
            // Re-enable buttons
            enableAllButtons();
        };
    }

    function formatLine(text, type) {
        switch (type) {
            case 'success':
                return '<span class="success-line">' + escapeHtml(text) + '</span>';
            case 'error':
                return '<span class="error-line">' + escapeHtml(text) + '</span>';
            case 'warning':
                return '<span style="color: #ffa500;">' + escapeHtml(text) + '</span>';
            case 'header':
                return '<span style="color: #00d4ff; font-weight: bold;">' + escapeHtml(text) + '</span>';
            case 'info':
            default:
                return escapeHtml(text);
        }
    }

    function clearCacheOnly() {
        const outputDiv = document.getElementById('update-output');
        const progressSection = document.getElementById('progress-section');
        
        progressSection.style.display = 'block';
        outputDiv.innerHTML = '🧹 Clearing all caches...\n';

        fetch('{{ route('system.update.clear-cache') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                outputDiv.innerHTML += '\n✅ All caches cleared successfully!\n';
                outputDiv.innerHTML += '\n' + data.output + '\n';
            } else {
                outputDiv.innerHTML += '\n❌ Cache clearing failed!\n';
                outputDiv.innerHTML += '\nError: ' + data.message + '\n';
            }
        })
        .catch(error => {
            outputDiv.innerHTML += '\n❌ Error: ' + error.message + '\n';
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function enableAllButtons() {
        document.getElementById('check-updates-btn').disabled = false;
        document.getElementById('update-btn').disabled = !hasUpdates;
        document.getElementById('force-update-btn').disabled = false;
        document.getElementById('clear-cache-btn').disabled = false;
    }
});
</script>
@endsection
