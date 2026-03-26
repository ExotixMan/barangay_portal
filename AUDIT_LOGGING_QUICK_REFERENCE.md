# Quick Reference: Adding AuditLogger to Controllers

## Step 1: Add Trait to Controller Class

For **EACH controller** you want to log actions in:

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YourModel;
use App\Traits\AuditLogger;  // ← ADD THIS LINE
use Illuminate\Http\Request;

class YourController extends Controller
{
    use AuditLogger;  // ← ADD THIS LINE
    
    // ... rest of controller
}
```

---

## Step 2: Add Audit Calls to Action Methods

### Pattern 1: CREATE (store method)

```php
public function store(Request $request)
{
    $validated = $request->validate([...]);
    
    $model = YourModel::create($validated);
    
    // Log the creation
    $this->auditLogCreate('ModuleName', $model->id, $validated);
    
    return redirect()->back()->with('success', 'Created successfully');
}
```

### Pattern 2: UPDATE (update method)

```php
public function update(Request $request, YourModel $model)
{
    $oldValues = $model->toArray();
    
    $validated = $request->validate([...]);
    $model->update($validated);
    
    // Log with change tracking
    $this->auditLogUpdate('ModuleName', $model->id, $oldValues, $validated);
    
    return redirect()->back()->with('success', 'Updated successfully');
}
```

### Pattern 3: DELETE (destroy method)

```php
public function destroy(YourModel $model)
{
    // Capture data before deletion
    $deletedData = $model->toArray();
    
    // Log the deletion
    $this->auditLogDelete('ModuleName', $model->id, $deletedData);
    
    $model->delete();
    
    return redirect()->back()->with('success', 'Deleted successfully');
}
```

---

## Step 3: Controllers to Implement (Copy-Paste Ready)

### 1. ProjectController
**File**: `app/Http/Controllers/Admin/ProjectController.php`

```php
// Add to top of class:
use App\Traits\AuditLogger;
// Add to class: use AuditLogger;

// In store():
$this->auditLogCreate('Projects', $project->id, $request->validated());

// In update():
$oldValues = $project->toArray();
// ... after $project->update()
$this->auditLogUpdate('Projects', $project->id, $oldValues, $request->validated());

// In destroy():
$this->auditLogDelete('Projects', $project->id, $project->toArray());

// In updateProgress(): (if exists)
$oldStatus = $project->status;
$project->update(['progress' => $request->progress]);
$this->auditLogUpdate('Projects', $project->id, 
    ['progress' => $oldStatus], 
    ['progress' => $request->progress]
);
```

### 2. AnnouncementController
**File**: `app/Http/Controllers/Admin/AnnouncementController.php`

```php
// Add to top of class:
use App\Traits\AuditLogger;
// Add to class: use AuditLogger;

// In store():
$this->auditLogCreate('Announcements', $announcement->id, $request->validated());

// In update():
$oldValues = $announcement->toArray();
// ... after $announcement->update()
$this->auditLogUpdate('Announcements', $announcement->id, $oldValues, $request->validated());

// In destroy():
$this->auditLogDelete('Announcements', $announcement->id, $announcement->toArray());
```

### 3. EventController
**File**: `app/Http/Controllers/Admin/EventController.php`

```php
// Add to top of class:
use App\Traits\AuditLogger;
// Add to class: use AuditLogger;

// In store():
$this->auditLogCreate('Events', $event->id, $request->validated());

// In update():
$oldValues = $event->toArray();
// ... after $event->update()
$this->auditLogUpdate('Events', $event->id, $oldValues, $request->validated());

// In destroy():
$this->auditLogDelete('Events', $event->id, $event->toArray());
```

### 4. ResidentController
**File**: `app/Http/Controllers/Admin/ResidentController.php`

```php
// Add to top of class:
use App\Traits\AuditLogger;
// Add to class: use AuditLogger;

// In store():
$this->auditLogCreate('Residents', $resident->id, $request->validated());

// In update():
$oldValues = $resident->toArray();
// ... after $resident->update()
$this->auditLogUpdate('Residents', $resident->id, $oldValues, $request->validated());

// In destroy():
$this->auditLogDelete('Residents', $resident->id, $resident->toArray());
```

### 5. WitnessController
**File**: `app/Http/Controllers/Admin/WitnessController.php`

```php
// Add to top of class:
use App\Traits\AuditLogger;
// Add to class: use AuditLogger;

// In store():
$this->auditLogCreate('Witnesses', $witness->id, $request->validated());

// In destroy():
$this->auditLogDelete('Witnesses', $witness->id, $witness->toArray());
```

---

## Implementation Checklist

For each controller in the list above:

- [ ] Add `use App\Traits\AuditLogger;` to imports
- [ ] Add `use AuditLogger;` to class body
- [ ] Add `$this->auditLogCreate(...)` to `store()` method
- [ ] Add `$this->auditLogUpdate(...)` to `update()` method (if exists)
- [ ] Add `$this->auditLogDelete(...)` to `destroy()` method (if exists)
- [ ] Test: Try create/update/delete in admin panel
- [ ] Verify: Check Audit Logs tab shows new entries

---

## Example: Complete ProjectController Implementation

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\AuditLogger;  // ← ADDED
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use AuditLogger;  // ← ADDED

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|in:planning,active,completed',
        ]);

        $project = Project::create($validated);
        
        // ← ADDED: Log the creation
        $this->auditLogCreate('Projects', $project->id, $validated);

        return redirect()->route('admin.projects.index')
                        ->with('success', 'Project created successfully');
    }

    public function update(Request $request, Project $project)
    {
        // ← ADDED: Capture old values
        $oldValues = $project->toArray();
        
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|in:planning,active,completed',
        ]);

        $project->update($validated);
        
        // ← ADDED: Log the update with change tracking
        $this->auditLogUpdate('Projects', $project->id, $oldValues, $validated);

        return redirect()->route('admin.projects.index')
                        ->with('success', 'Project updated successfully');
    }

    public function destroy(Project $project)
    {
        // ← ADDED: Capture data before deletion
        $this->auditLogDelete('Projects', $project->id, $project->toArray());
        
        $project->delete();

        return redirect()->route('admin.projects.index')
                        ->with('success', 'Project deleted successfully');
    }
}
```

---

## Verification Steps

After implementing in a controller:

### 1. Create a new record
- Navigate to admin panel
- Create a new project/announcement/event
- Check it appears in the list

### 2. Check audit logs
- Navigate to: Admin > Users & Roles > Audit Logs tab
- Look for entry with:
  - Module: "Projects" (or your module name)
  - Action: "CREATE"
  - User: (your admin name)

### 3. Update the record
- Edit the record you just created
- Check Audit Logs for UPDATE entry

### 4. Delete the record
- Delete the record
- Check Audit Logs for DELETE entry

---

## Module Name Reference

Use these exact module names for consistency:

```
'Projects' -> ProjectController
'Announcements' -> AnnouncementController
'Events' -> EventController
'Residents' -> ResidentController
'Witnesses' -> WitnessController
'Requests' -> RequestController
'Indigency' -> IndigencyController (already has logging)
'Residency' -> ResidencyController (already has logging)
'Clearance' -> ClearanceController (already has logging)
'Blotter' -> IncidentReportController (already has logging)
'Notifications' -> NotificationController (already has logging)
'Users' -> AdminUserController (already has logging)
'Roles' -> AdminRoleController
'Permissions' -> AdminPermissionController
```

---

## Batch Implementation (All at Once)

To implement across all 5 controllers:

1. **For each of these files:**
   - `app/Http/Controllers/Admin/ProjectController.php`
   - `app/Http/Controllers/Admin/AnnouncementController.php`
   - `app/Http/Controllers/Admin/EventController.php`
   - `app/Http/Controllers/Admin/ResidentController.php`
   - `app/Http/Controllers/Admin/WitnessController.php`

2. **Add these 2 lines at the top of the class:**
   ```php
   use App\Traits\AuditLogger;
   ```
   ```php
   use AuditLogger;
   ```

3. **Add to store() method:**
   ```php
   $this->auditLogCreate('ModuleName', $model->id, $validated);
   ```

4. **Add to update() method:**
   ```php
   $oldValues = $model->toArray();
   // ... update code ...
   $this->auditLogUpdate('ModuleName', $model->id, $oldValues, $validated);
   ```

5. **Add to destroy() method:**
   ```php
   $this->auditLogDelete('ModuleName', $model->id, $model->toArray());
   ```

6. **Run tests** to verify all actions are logged

---

## Troubleshooting

**Q: Audit logs not showing up?**
A: Make sure:
1. You added `use AuditLogger;` to the class body
2. You called `$this->auditLog...()` method in the action method
3. You're logged in as admin (audit logs only log admin actions)

**Q: Getting "Call to undefined method" error?**
A: Make sure the trait is added to the controller class:
```php
use App\Traits\AuditLogger;  // In imports
use AuditLogger;  // In class body
```

**Q: How do I see the audit logs?**
A: Navigate to Admin Panel → Users & Roles → Click "Audit Logs" tab

**Q: Can I filter audit logs by module?**
A: Yes! In Audit Logs tab, use the "Module" filter dropdown to show only "Projects", "Events", etc.

---

## File Paths Summary

- Trait to add: `app/Traits/AuditLogger.php` ✅ Already created
- Implementation guide: `IMPLEMENTATION_GUIDE.md` ✅ Already created
- Controllers to update (examples above):
  - `app/Http/Controllers/Admin/ProjectController.php`
  - `app/Http/Controllers/Admin/AnnouncementController.php`
  - `app/Http/Controllers/Admin/EventController.php`
  - `app/Http/Controllers/Admin/ResidentController.php`
  - `app/Http/Controllers/Admin/WitnessController.php`

**Everything is ready to use! Copy-paste the code snippets above into your controllers.** ✅
