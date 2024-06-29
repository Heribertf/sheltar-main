<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            Sheltar<span>Properties</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Home</li>
            <li class="nav-item">
                <a href="./dashboard" class="nav-link">
                    <i class="link-icon" data-feather="grid"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#listings" role="button" aria-expanded="false"
                    aria-controls="listings">
                    <i class="link-icon" data-feather="list"></i>
                    <span class="link-title">Property Listings</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="listings">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="./active-listings" class="nav-link">Active Listings</a>
                        </li>
                        <li class="nav-item">
                            <a href="./pending-review" class="nav-link">Pending Review</a>
                        </li>
                        <li class="nav-item">
                            <a href="./rejected-listings" class="nav-link">Rejected</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#verification" role="button" aria-expanded="false"
                    aria-controls="verification">
                    <i class="link-icon" data-feather="user-check"></i>
                    <span class="link-title">Agent Verification</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="verification">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="./verified-agents" class="nav-link">Verified Agents</a>
                        </li>
                        <li class="nav-item">
                            <a href="./pending-verification" class="nav-link">Pending Verification</a>
                        </li>
                        <li class="nav-item">
                            <a href="./rejected-agents" class="nav-link">Rejected</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="./plans" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Plans</span>
                </a>
            </li>
            <li class="nav-item nav-category">System</li>
            <li class="nav-item">
                <a href="./my-profile" class="nav-link">
                    <i class="link-icon" data-feather="user"></i>
                    <span class="link-title">Manage Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="./logout" class="nav-link">
                    <i class="link-icon" data-feather="log-out"></i>
                    <span class="link-title">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>