
#include <EtherCard.h>
#include <dht.h>
#include <Servo.h> 

dht DHT;
#define dht_apin A1   //sensor temp and huminity
int servoPin = 4; 
int buzzer = 7; // set the buzzer control digital IO pin
Servo Servo1;
char alarm = '0';

// defines pins numbers

const int trigPin = 8;
const int echoPin = 9;
int sensorPin = A0; // select the input pin for LDR
int light = 0; // variable to store the value coming from the sensor
const int gasPin = A5; //GAS sensor output pin to Arduino analog A5 pin

// ethernet interface mac address, must be unique on the LAN
static byte mymac[] = { 0x74,0x69,0x70,0x2D,0x30,0x40 };

byte Ethernet::buffer[400];
byte ebuff[400];
static uint32_t timer;

const char website[] PROGMEM = "zafora.icte.uowm.gr";

// called when the client request is complete
static void my_callback (byte status, word off, word len) {
  Serial.println(">>>");
  Ethernet::buffer[off+400] = 0;
  //Serial.print((const char*) Ethernet::buffer + off);
  //Serial.println("...");

  //read var
  memcpy( ebuff, Ethernet::buffer,sizeof(Ethernet::buffer));
  ebuff[off+400] = 0;
  Ethernet::buffer[0] = 0;
  Ethernet::buffer[off] = 0;
    Serial.print((const char*) ebuff + off);

  boolean flag = false;
  int flag_servo = 5;
  int flag_relay2 = 5;
  int k = 0;
  for(int i=0; i<400; i++){
    
    if(flag==false){
      
      if (ebuff[i]=='~'){
       
  
        k=k+1;
       
        if (k==1){
          
        flag_servo = ebuff[i+1]-48;
        flag_relay2 = ebuff[i+2]-48;
        
 // Serial.println(ebuff[i+1]);
  //Serial.println(ebuff[i+2]);
  //Serial.println(ebuff[i+3]);
          }
        //flag = true;
        delay(5);
      }
    }
  }
  Serial.println("rele");
  Serial.println(flag_relay2);
  

  //actions
  if (flag_servo==1){
    Servo1.write(160); 
    Serial.print("ok");
    delay(1000); 
  }
  else if (flag_servo==2){
    Serial.print("ok1");
    Servo1.write(90); 
    delay(1000); 
  }

    digitalWrite(6, HIGH); 
    delay(1000); 
    digitalWrite(6, LOW);
    delay(1000); 

  if (flag_relay2==1){
    digitalWrite(6, LOW); 
    Serial.print("ok2");
    delay(1000); 
  }
  else{
    digitalWrite(6, HIGH); 
    delay(1000); 
  }
  

}
void setup () {
  Serial.begin(57600);
  Serial.println(F("\n[webClient]"));

  if (ether.begin(sizeof Ethernet::buffer, mymac) == 0) 
    Serial.println(F("Failed to access Ethernet controller"));
  if (!ether.dhcpSetup())
    Serial.println(F("DHCP failed"));

  ether.printIp("IP:  ", ether.myip);
  ether.printIp("GW:  ", ether.gwip);  
  ether.printIp("DNS: ", ether.dnsip);  

#if 1
  // use DNS to resolve the website's IP address
  if (!ether.dnsLookup(website))
    Serial.println(F("DNS failed"));
#elif 2
  // if website is a string containing an IP address instead of a domain name,
  // then use it directly. Note: the string can not be in PROGMEM.
  char websiteIP[] = "192.168.1.1";
  ether.parseIp(ether.hisip, websiteIP);
#else
  // or provide a numeric IP address instead of a string
  byte hisip[] = { 192,168,1,1 };
  ether.copyIp(ether.hisip, hisip);
#endif
    
  ether.printIp("SRV: ", ether.hisip);

  //setup sensors
  Servo1.attach(servoPin); 
  pinMode(trigPin, OUTPUT); // Sets the trigPin as an Output
  pinMode(echoPin, INPUT); // Sets the echoPin as an Input
   pinMode(6, OUTPUT);
   pinMode (13, OUTPUT); // define the digital output interface 13 feet
   digitalWrite (13, HIGH); // open the laser head
    pinMode(buzzer, OUTPUT); // set pin 8 as output
}

void loop () {

  //Sensors
  //temperature & hymidity
    DHT.read11(dht_apin);
    /*Serial.print(F("humidity: "));
    Serial.println(DHT.humidity);
    
    Serial.print(F("temp: "));
    Serial.println(DHT.temperature);
    */
    //delay(500);
    // defines variables
    /*long duration;
    int distance;
    // Clears the trigPin
    digitalWrite(trigPin, LOW);
    delayMicroseconds(2);
    // Sets the trigPin on HIGH state for 10 micro seconds
    digitalWrite(trigPin, HIGH);
    delayMicroseconds(10);
    digitalWrite(trigPin, LOW);
    // Reads the echoPin, returns the sound wave travel time in microseconds
    duration = pulseIn(echoPin, HIGH);
    // Calculating the distance
    distance= duration*0.034/2;
    // Prints the distance on the Serial Monitor
    //Serial.print(F("Distance: "));
    //Serial.println(distance); 
     //delay(1000);
   */
    light = analogRead(sensorPin); // read the value from the sensor LASER
    //Serial.println(light); //prints the values coming from the sensor on the screen
    

    char gas;
    //Serial.println(analogRead(gasPin));
    if(analogRead(gasPin)>120){
      gas = 1;
      for (int i = 0; i < 80; i++) {  // make a sound
        digitalWrite(buzzer, HIGH); // send high signal to buzzer 
        delay(1); // delay 1ms
        digitalWrite(buzzer, LOW); // send low signal to buzzer
        delay(1);
      }
      alarm = '1';
    }
    //delay(1000);
    
    
    char socket;
    
/*    if(distance<20){
      socket = '1';
    
   delay(1000);
    }
    else{
      socket = '0';  
    }*/socket = '1';
 

    //memset(DataCombination,0,sizeof(DataCombination));
    String DataCombination = String(DHT.temperature) + "&hum=" + String(DHT.humidity) + "&socket=" + socket + "&light=" + light + "&smoke=" + alarm ;
    int DataCombination_len = DataCombination.length() + 1;
    char DataChar[DataCombination_len];
    DataCombination.toCharArray(DataChar, DataCombination_len);
    
  delay(100);
  //Ethernet
  
  word pos = ether.packetLoop(ether.packetReceive());

  
  if (millis() > timer) {
    timer = millis() + 5000;
    Serial.println();
    Serial.print(F("<<< REQ "));
    ether.browseUrl(PSTR("/~ictest00754/greecomnia/actions/connect_sensor.php?plug=5&ardid=2&location=livingroom&idsensor1=4&type1=1&type2=2&idsensor2=2&type3=3&idsensor3=67&sensor3=3&idsensor4=4&sensor4=4&type4=4&temp="), DataChar, website, my_callback);
  }
  alarm = '0';

  
}
