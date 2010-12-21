
import argparse
import pymongo
import datetime
import yaml

class SpectorCli:
  
  severities = ('EMERGENCY', 'CRITICAL', 'ERROR', 'WARNING', 'NOTICE', 'INFO', 'DEBUG', 'OTHER')
  
  def run(self):
    parser = self.getParser()
    args = self.namespaceToDict(parser.parse_args())
    
    if args['config']:
        args = self.loadConfigArgs(args)
    
    try: args = self.validate(args)
    except Exception as e:
      print e
      return
    
    
    
    self.log(args)
    
  def loadConfigArgs(self, args):
    configArgs = yaml.load(open(args['config']))
    
    for key in configArgs:
        if not args[key]: args[key] = configArgs[key]
    
    return args    
      
  def log(self, args):
      conn = pymongo.Connection(args['host'], args['port'])
      
      db = conn[args['database']]
      collection = db['log_entries']
      
      entry = self.getEntryFromArgs(args)      
      collection.insert(entry)
      
  def getEntryFromArgs(self, args):
      
      entry = dict()
      
      for key in args:
          if key in ('project', 'environment', 'bucket', 'type', 'time', 'severity', 'message', 'data'):
              entry[key] = args[key]
      
      entry['time'] = datetime.datetime.now()
      
      return entry
    
  def namespaceToDict(self, namespace):
    dict = {"config": namespace.config, "database": namespace.database,"host": namespace.host, "port": namespace.port, "project": namespace.project, "environment": namespace.environment, "bucket": namespace.bucket, "severity": namespace.severity, "type": namespace.type, "message": namespace.message, "data": namespace.data}
    
    return dict

  def getParser(self):
    parser = argparse.ArgumentParser(description='Spector logging.')
    parser.add_argument('--config', '-c', dest='config', action='store',
                       help='YAML config file containing db settings etc.')
    parser.add_argument('--database', dest='database', action='store',
                       help='database name')
    parser.add_argument('--host', dest='host', action='store',
                       help='db host, defaults to localhost')
    parser.add_argument('--port', dest='port', action='store',
                       help='db port, defaults to 27017')
    
    parser.add_argument('--project', '-p', dest='project', action='store',
                       help='The project')
    parser.add_argument('--env', '-e', dest='environment', action='store',
                       help='The environment')
    parser.add_argument('--bucket', '-b', dest='bucket', action='store',
                       help='The bucket')
    parser.add_argument('--severity', '-s', dest='severity', action='store',
                       help='The severity')
    parser.add_argument('--type', '-t', dest='type', action='store',
                       help='The type')
    parser.add_argument('--data', dest='data', action='store',
                       help='The data')     
    parser.add_argument('message', action='store',
                       help='The message')
    return parser  
  
  def validate(self, args):
    if not args['host']:
      args['host'] = "localhost"  
      
    if not args['port']:
      args['port'] = 27017 
      
    if not args['type']:
      args['type'] = 'log'
    
    if not args['bucket']:
      args['bucket'] = 'main'
    
    for key in ('database', 'project', 'environment', 'severity', 'type'):
      if not args[key]:
          raise Exception("Required option not specified: " + key)
    
    args['severity'] = args['severity'].upper()
    
    if args['severity'] not in self.severities:
      raise Exception("Unknown severity '" + args['severity'] + "'. Allowed values: " + ', '.join(self.severities))
      
    return args
  
cli = SpectorCli()
cli.run()
  