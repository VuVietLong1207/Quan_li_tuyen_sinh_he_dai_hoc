Copy the `demo1707` project files here for integration.

How to copy from your current path (PowerShell):

# If demo1707 is at c:\xampp\htdocs\demo1707
Copy-Item -Path "C:\xampp\htdocs\demo1707\*" -Destination "C:\xampp\htdocs\BTl\imported_demo\" -Recurse

# Or move it:
Move-Item -Path "C:\xampp\htdocs\demo1707" -Destination "C:\xampp\htdocs\BTl\imported_demo\demo1707"

After copying, tell me and I'll:
- scan the copied files,
- create adapted skeleton files inside `BTl` (method B), and
- start merging/adapting files (method A) to match `BTl` structure.
