<aside class="zoho-widgets-pane">
    <div class="zoho-widget-ask">
        <h3 style="margin:0 0 6px;font-size:14px;color:#fff;">Ask Zia</h3>
        <p>Get instant answers about your gallery finances and inventory.</p>
        <input type="text" placeholder="Ask me anything..." aria-label="Ask Zia">
    </div>

    <div class="zoho-widget-section">
        <h3>Tasks</h3>
        <div class="zoho-list-item">
            <div class="zoho-list-icon">✓</div>
            <div class="zoho-list-content">
                <div class="zoho-list-title">Review pending invoices</div>
                <div class="zoho-list-meta">Due today</div>
            </div>
        </div>
        <div class="zoho-list-item">
            <div class="zoho-list-icon">!</div>
            <div class="zoho-list-content">
                <div class="zoho-list-title">Update artwork catalog</div>
                <div class="zoho-list-meta">3 items need photos</div>
            </div>
        </div>
    </div>

    <div class="zoho-widget-section">
        <h3>Recent Documents</h3>
        <a href="{{ route('admin.documents') }}" class="zoho-list-item" style="text-decoration:none;color:inherit;">
            <div class="zoho-list-icon">📄</div>
            <div class="zoho-list-content">
                <div class="zoho-list-title">Q1 Sales Report.pdf</div>
                <div class="zoho-list-meta">Uploaded 2 days ago</div>
            </div>
        </a>
    </div>

    <div class="zoho-widget-section">
        <h3>Contextual Chat</h3>
        <p style="font-size:12px;color:var(--zoho-text-muted);margin:0;">Collaborate with your team on records and transactions.</p>
        <button type="button" class="zoho-btn zoho-btn-secondary" style="margin-top:10px;width:100%;justify-content:center;">Open Chat</button>
    </div>
</aside>
