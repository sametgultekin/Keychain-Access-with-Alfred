# 
#  functions.rb
#  Keychain Access With Alfred
#  
#  Created by Samet Gültekin on 2012-01-06.
#  Copyright 2012 Samet Gültekin. All rights reserved.
# 

def openAppleDialog(question, defaultAnswer)
  cmd = `
  osascript -e 'tell app "System Events" to display dialog "#{question}" default answer "#{defaultAnswer}"' \
            -e 'set theAnswer to (text returned of result)' \
            -e 'theAnswer'
  `
  
  cmd1 = cmd.scan(/(.*)\\n/)
  cmd1
end

def addNewRecord(name, account, password, comment, keychain)
  cmd = "security 2>&1 >/dev/null add-generic-password -s \"#{name}\" -a \"#{account}\" -w \"#{password}\" -j \"#{comment}\" ./#{keychain}"
  #puts cmd
  `#{cmd}`
end

def findRecord(name, keychain)
  
  command_pass = "security  2>&1 >/dev/null -q  find-generic-password -gl \"#{name}\" ./#{keychain}"
  result_pass = `#{command_pass}`
  password = result_pass.scan(/password: "(.*)"/)
  return nil if password.empty?
  
  
  command_info = "security -q  find-generic-password -gl \"#{name}\" ./#{keychain}"
  
  result_info = `#{command_info}`
  label = result_info.scan(/0x00000007 <blob>="(.*)"/)
  acc = result_info.scan(/"acct"<blob>="(.*)"/)
  ser = result_info.scan(/"svce"<blob>="(.*)"/)
  cmt = result_info.scan(/"icmt"<blob>="(.*)"/)
  
  puts "Password for #{label} \n\n Account: #{acc} \n Service: #{ser} \n Comment: #{cmt}"
  
  puts "\nPassword is copied to clipboard"
  
  # command_copy_clipboard = "echo \"#{password}\" | pbcopy"
  #   
  #   result_copy_clipboard = `#{command_copy_clipboard}`
  
  command_copy_clipboard "osascript -e do shell script \"echo hello | pbcopy\""
  result_copy_clipboard = `#{command_copy_clipboard}`
  
  
  return "true"
end