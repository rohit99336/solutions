To host a GoDaddy subdomain on Hostinger, you'll need to make some changes to your DNS settings in both your GoDaddy account and your Hostinger hosting account. Here are the steps to do this:

**Step 1: Get Your Hostinger DNS Information**
1. Log in to your Hostinger account.
2. Access your hosting control panel (usually cPanel).
3. Look for the DNS information for your hosting account. This typically includes the nameservers (e.g., ns1.hostinger.com, ns2.hostinger.com) and the IP address of your hosting server.

**Step 2: Update DNS Settings on GoDaddy**
1. Log in to your GoDaddy account.
2. Go to the "My Products" section and find the domain associated with the subdomain you want to point to Hostinger.
3. Click on the domain to access its settings.
4. Look for an option like "Manage DNS" or "DNS Settings." Click on it.
5. In the DNS settings, locate the section where you can change the nameservers.
6. Change the nameservers to the ones provided by Hostinger (e.g., ns1.hostinger.com, ns2.hostinger.com).
7. Save your changes.

Note: DNS changes may take some time to propagate across the internet. This process can take anywhere from a few minutes to 48 hours, so be patient.

**Step 3: Add the Subdomain in Hostinger**
1. Go back to your Hostinger hosting control panel (cPanel).
2. Find the option to add a subdomain. It's usually under the "Domains" or "Subdomains" section.
3. Create the subdomain you want to host. For example, if your subdomain is "sub.example.com," you would add "sub" as the subdomain.
4. Configure the subdomain settings as needed. You may need to specify a directory where the subdomain's files will be stored.

**Step 4: Upload Your Website Files**
If you have website files for your subdomain, you'll need to upload them to your Hostinger hosting account. You can use FTP or a file manager in your hosting control panel to do this.

**Step 5: Test the Subdomain**
Once you've made these changes, you can test your subdomain by entering it into your web browser. It should now point to your Hostinger hosting account.

Keep in mind that DNS changes may take some time to propagate fully, so the subdomain may not work immediately. Be patient, and it should resolve to your Hostinger hosting within a few hours to a couple of days, depending on DNS propagation times.

If you encounter any issues or have specific questions about your Hostinger or GoDaddy account settings, it's a good idea to contact their customer support for assistance.