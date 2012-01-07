############ 

OPTIONS = {
  :keychain => "samet.keychain",
  
}
$keychain = "login.keychain"

############
require 'functions.rb'

# def clear_strings(arr)
#   arr.each do  |str|
#     before = str
#     after = str.gsub(/[^a-zA-Z0-9]+/, '')
#     after = str.gsub("\n", '')
#     # # puts  "before: #{before}, after: #{after}"
#   end
# end

args = ARGV[0].split(" ")
action = args.first

if action.eql? "add"
  name = openAppleDialog("Enter a name", "i.e: gmail")
  return nil if name.empty?
  account = openAppleDialog("Enter account name", "i.e: johndoe@gmail.com")
  return nil if account.empty?
  where  =  openAppleDialog("Enter domain", "i.e: http://www.google.com")
  return nil if where.empty?
  password = openAppleDialog("Enter password", "i.e: 123123123")
  return nil if password.empty? 
  comment = openAppleDialog("Enter comment", "lorem ipsum")
  return nil if comment.empty?

  # puts "*******"
  #   puts "before: #{name}, #{account}, #{password}, #{comment}"
  #   clear_strings [name, account, password, comment]
  #   puts "after: #{name}, #{account}, #{password}, #{comment}"
  result = addNewRecord(name, account, password, comment, OPTIONS[:keychain])
  # result = addNewRecord("birad", "biraccount", "birpass", "birkomment", OPTIONS[:keychain])
  puts "New record added to keychain" if result.empty?

  puts "add record output> #{result}"

elsif action.eql? "get"
  result = findRecord(args[1], OPTIONS[:keychain])
  puts "Password is not found" if result.nil?
else
  puts "eror"
end

