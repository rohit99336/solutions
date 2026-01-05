## How to Uninstall or Remove Cursodr IDE from Ubuntu 

To uninstall or remove Cursodr IDE from your Ubuntu system, you can follow these steps:
1. **Open Terminal**: You can open the terminal by pressing `Ctrl + Alt + T` on your keyboard.
2. **Remove Cursodr IDE**: Depending on how you installed Cursodr IDE, you can use one of the following commands:

   - If you installed it via `apt`:
     ```bash
     sudo apt remove curosr
     ```

   - If you installed it via `snap`:
     ```bash
     sudo snap remove curosr
     ```

   - If you installed it via a `.deb` package:
     ```bash
     sudo dpkg -r curosr
     ```
3. **Manual Removal** (Optional): If you want to remove all configuration files associated with Cursodr IDE, you can delete the configuration directory:
    

    1.Remove the main application file: Delete the actual Cursor AppImage file from wherever you saved it (e.g., ~/Downloads, ~/Applications, or /opt). 

        
        rm -rf /path/to/Cursor-*.AppImage
        
    2.Delete configuration and cache files: Cursor may store configuration files in your home directory. You can remove these directories to completely uninstall Cursor.

        
        rm -rf ~/.cursor*
        rm -rf ~/.config/Cursor
        rm -rf ~/.local/share/Cursor
        rm -rf ~/.cache/Cursor
    
    The ~/.cursor* command handles folders like ~/.cursor which contain extensions.

    **Remove desktop entry and symlinks:**  If you created a desktop entry to make it appear in your applications menu or a symlink to run it from the terminal, remove those as well:

        sudo rm -f /usr/local/bin/cursor
        rm -f ~/.local/share/applications/cursor.desktop
        sudo rm -f /usr/share/applications/cursor.desktop

    (Only run the sudo commands if you used sudo during the initial setup to place files in system directories).

    **Update the desktop database:**

        update-desktop-database ~/.local/share/applications

     **Clean Up**: If, for some reason, you managed to install it via a .deb package using apt, you can use the standard package manager removal command: 

        sudo apt remove --purge cursor

    After using any of these methods, the Cursor IDE should be completely removed from your system.

        